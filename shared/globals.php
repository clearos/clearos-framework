<?php

/**
 * ClearOS framework core settings and functions.
 *
 * The functions and environment in this file are shared by both the base API
 * and the CodeIgniter engine. 
 *
 * @category   Framework
 * @package    Shared
 * @subpackage Helpers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/framework/
 */

//////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU Lesser General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Lesser General Public License for more details.
//
// You should have received a copy of the GNU Lesser General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

use \clearos\framework\Config as Config;
use \clearos\framework\Error as Error;
use \clearos\framework\Lang as Lang;
use \clearos\framework\Logger as Logger;

require_once 'libraries/Config.php';
require_once 'libraries/Error.php';
require_once 'libraries/Lang.php';
require_once 'libraries/Logger.php';

///////////////////////////////////////////////////////////////////////////////
// G L O B A L  C O N S T A N T S
///////////////////////////////////////////////////////////////////////////////

define('CLEAROS_ERROR', -1);
define('CLEAROS_WARNING', -2);
define('CLEAROS_INFO', -4);
define('CLEAROS_DEBUG', -8);

// FIXME: COMMON_CORE_DIR remove references to this
define('COMMON_CORE_DIR', Config::$framework_path . '/application/libraries');

// FIXME: move to Config if still required
define("COMMON_TEMP_DIR", "/usr/webconfig/tmp");

///////////////////////////////////////////////////////////////////////////////
// G L O B A L  I N I T I A L I Z A T I O N
///////////////////////////////////////////////////////////////////////////////

// The date_default_timezone_set must be called or the time zone must be set
// in PHP's configuration when date() functions are called.  On a ClearOS 
// system, the default time zone for the system is correct.

@date_default_timezone_set(@date_default_timezone_get());

// Set error and exception handlers
//---------------------------------

set_error_handler("_clearos_error_handler");
set_exception_handler("_clearos_exception_handler");

// Logging
//--------

if (Config::$debug_mode) {
    @ini_set('display_errors', TRUE); 
    @ini_set('display_startup_error', TRUE);
    @ini_set('log_errors', TRUE);
    @ini_set('error_log', Config::$debug_log);
}

///////////////////////////////////////////////////////////////////////////////
// G L O B A L  F U N C T I O N S
///////////////////////////////////////////////////////////////////////////////

/**
 * Generates profiling data. 
 *
 * @param string $method  method name
 * @param string $line    line number
 * @param string $message additional profiling information
 *
 * @return void
 */

function clearos_profile($method, $line, $message = NULL)
{
    Logger::profile($method, $line, $message);
}

/**
 * Emits deprecated method call warnings
 *
 * @param string $method  method name
 * @param string $line    line number
 * @param string $message additional profiling information
 *
 * @return void
 */

function clearos_deprecated($method, $line, $message = 'called deprecated method')
{
    Logger::deprecated($method, $line, $message);
}

/**
 * Loads a language file.
 *
 * CodeIgniter defines the global lang() function for translations.  If the
 * CodeIgniter framework is already initialized, its lang() framework is used.
 * If the CodeIgniter framework is not in use, then the following provides this 
 * same functionality for the API without having to pull in big chunks of the 
 * CodeIgniter framework.
 *
 * @param string $lang_file language file
 *
 * @return void
 */

function clearos_load_language($lang_file)
{
    global $clearos_lang;

    // Define the lang() function if it does not exist
    //------------------------------------------------

    if (! function_exists('lang')) {

        // Create lang object
        $clearos_lang = new Lang();

        /**
         * Translation lookup
         *
         * @param string $key language key
         *
         * @return string translation
         */

        function lang($key)
        {
            global $clearos_lang;
            return $clearos_lang->line($key);
        }
    }

    // Load language - CodeIgniter access, or direct access
    //-----------------------------------------------------

    if (isset($_SERVER['REQUEST_URI'])) {
        include_once BASEPATH . '/core/CodeIgniter.php';
        $codeigniter =& get_instance();
        $codeigniter->lang->load($lang_file);
    } else if (isset($clearos_lang)) {
        $clearos_lang->load($lang_file);
    }
}

/**
 * Pulls in a library.
 *
 * This function makes it possible to load different library versions -
 * a very useful feature in development environments.
 *
 * @param string $library library path
 *
 * @return void
 */

function clearos_load_library($library)
{
    list($app, $library) = preg_split('/\//', $library, 2);

    // FIXME: point to online document on what's going on here
    if (!empty(Config::$clearos_devel_versions['app'][$app]))
        $version = Config::$clearos_devel_versions['app'][$app];
    else if (!empty(Config::$clearos_devel_versions['app']['default']))
        $version = Config::$clearos_devel_versions['app']['default'];
    else
        $version = '';

    include_once Config::$apps_path . "/$app/$version/libraries/$library.php";
}

/**
 * Returns the error message from any Exception object
 *
 * This function makes it possible to return the error message from
 * an Exception object regardless if it is ours (derived from Engine_Exception),
 * or if comes from some other third-party code (with only getMessage()).
 *
 * @param   object $exception exception object
 * @return  string exception message
 */

function clearos_exception_message($exception)
{
    if (is_object($exception)) {
        if (method_exists($exception, 'get_message')) return $exception->get_message();
        else if (method_exists($exception, 'getMessage')) return $exception->getMessage();
    }
    return '';
}

///////////////////////////////////////////////////////////////////////////////
// G L O B A L  E R R O R  A N D  E X C E P T I O N  H A N D L E R S
///////////////////////////////////////////////////////////////////////////////

/** 
 * Error handler used by set_error_handler().
 *
 * @param integer $errno   error number
 * @param string  $errmsg  error message
 * @param string  $file    file name where occurred
 * @param integer $line    line in file where the error occurred
 * @param array   $context entire context where error was triggered
 * 
 * @access private
 * @return void
 */

function _clearos_error_handler($errno, $errmsg, $file, $line, $context)
{
    // If the @ symbol was used to suppress errors, bail
    //--------------------------------------------------

    /* FIXME -- revisit this ... it seems to suppress too much.
    if (error_reporting(0) === 0)
        return;
    */

    // Log the error
    //--------------

    $error = new Error($errno, $errmsg, $file, $line, $context, Error::TYPE_ERROR, FALSE);
    Logger::log($error);

    // Show error on standard out if running from command line
    //--------------------------------------------------------

    if (preg_match('/cli/', php_sapi_name())) {
        $errstring = $error->get_code_string();
        echo "$errstring: $errmsg - $file ($line)\n";
    }
}

/**
 * Exception handler used by set_exception_handler().
 * 
 * @param Exception $exception exception object
 *
 * @access private
 * @return void
 */

function _clearos_exception_handler(Exception $exception)
{
    // Log the exception
    //------------------

    Logger::log_exception($exception, FALSE);

    // Show error on standard out if running from command line
    //--------------------------------------------------------

    if (preg_match('/cli/', php_sapi_name()))
        echo 'Fatal - uncaught exception: ' . $exception->getMessage() . "\n";
    else
        echo '<div>Ooooops: ' . $exception->getMessage() . '</div>';
}

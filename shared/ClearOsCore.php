<?php

//////////////////////////////////////////////////////////////////////////////
//
// Copyright 2006, 2010 ClearFoundation
//
///////////////////////////////////////////////////////////////////////////////

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

/**
 * ClearOS framework core settings and functions. 
 *
 * The functions and environment in this file are shared by both the base API
 * and the CodeIgniter engine. 
 *
 * @package Framework
 * @author {@link http://www.foundation.com/ ClearFoundation}
 * @license http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @copyright Copyright 2006, 2010 ClearFoundation
 */

//////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

require_once('ClearOsConfig.php');
require_once('ClearOsError.php');
require_once("ClearOsLang.php");
require_once('ClearOsLogger.php');

///////////////////////////////////////////////////////////////////////////////
// C O N F I G U R A T I O N
///////////////////////////////////////////////////////////////////////////////

// Configuration is handled by the bootstrap.php process.

// FIXME: COMMON_CORE_DIR remove references to this
define('COMMON_CORE_DIR', ClearOsConfig::$framework_path . '/application/libraries');

// FIXME: move to ClearOsConfig if still required
define("COMMON_TEMP_DIR", "/usr/webconfig/tmp");

// FIXME - transition only... remove
define('COMMON_DEBUG_MODE', true);

///////////////////////////////////////////////////////////////////////////////
// T I M E  Z O N E
///////////////////////////////////////////////////////////////////////////////

// date_default_timezone_set must be called or the time zone must be set
// in PHP's configuration when date() functions are called.  On a ClearOS 
// system, the default time zone for the system is correct.

@date_default_timezone_set(@date_default_timezone_get());

///////////////////////////////////////////////////////////////////////////////
// L O G G I N G
///////////////////////////////////////////////////////////////////////////////

// FIXME: this might be a temporary hack... test it
@ini_set('include_path', '.');

if (ClearOsConfig::$debug_mode) {
	@ini_set('display_errors', TRUE); 
	@ini_set('display_startup_error', TRUE);
	@ini_set('log_errors', TRUE);
	@ini_set('error_log', ClearOsConfig::$debug_log);
}

///////////////////////////////////////////////////////////////////////////////
// L A N G U A G E  S U P P O R T
///////////////////////////////////////////////////////////////////////////////
//
// CodeIgniter defines the global lang() function for translations.  If the
// CodeIgniter framework is already initialized, its lang() framework is used.
// If the CodeIgniter framework is not in use, then the following provides this 
// same functionality for the API without having to pull in big chunks of the 
// CodeIgniter framework.

$clearos_lang = new ClearOsLang();

function clearos_load_language($langfile)
{
	global $clearos_lang;

	// Define the lang() function if it does not exist
	//------------------------------------------------

	if (! function_exists('lang')) {
		function lang($key) {
			global $clearos_lang;
			return $clearos_lang->line($key);
		}
	}

	// Load the language file
	//-----------------------

	if (isset($clearos_lang)) {
		$clearos_lang->load($langfile);
	} else {
		require_once(ClearOsConfig::$framework_path . '/system/core/CodeIgniter.php');
		$codeigniter =& get_instance();
		$codeigniter->lang->load($langfile);
	}
}

///////////////////////////////////////////////////////////////////////////////
// L I B R A R Y  L O A D E R
///////////////////////////////////////////////////////////////////////////////

/**
 * Pulls in a library.
 *
 * This function makes it possible to load different library versions -
 * a very useful feature in development environments.
 */

function clearos_load_library($fulllibrary) {
	list($app, $library) = split('/', $fulllibrary);

	// FIXME: point to online document on what's going on here
	if (!empty(ClearOsConfig::$clearos_devel_versions['app'][$app]))
		$version = ClearOsConfig::$clearos_devel_versions['app'][$app];
	else if (!empty(ClearOsConfig::$clearos_devel_versions['app']['default']))
		$version = ClearOsConfig::$clearos_devel_versions['app']['default'];
	else
		$version = '';

	require_once(ClearOsConfig::$apps_path . '/' . $app . '/' . $version . '/libraries/' . $library . '.php');
}

///////////////////////////////////////////////////////////////////////////////
// E R R O R  A N D  E X C E P T I O N  H A N D L E R S
///////////////////////////////////////////////////////////////////////////////

/** 
 * Error handler used by set_error_handler().
 *
 * @access private
 * @param integer $errno error number
 * @param string $errmsg error message
 * @param string $file file name where occurred
 * @param integer $line line in file where the error occurred
 * @param array $context entire context where error was triggered
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

	$error = new ClearOsError($errno, $errmsg, $file, $line, $context, ClearOsError::TYPE_ERROR, FALSE);
	ClearOsLogger::Log($error);

	// Show error on standard out if running from command line
	//--------------------------------------------------------

	if (preg_match('/cli/', php_sapi_name())) {
		$errstring = $error->GetCodeString();
		echo $errstring . ": " . $errmsg . " - $file ($line)\n";
	}
}

/**
 * Exception handler used by set_exception_handler().
 * 
 * @access private
 * @param Exception $exception exception object
 */

function _clearos_exception_handler(Exception $exception)
{
	// Log the exception
	//------------------

	ClearOsLogger::LogException($exception, TRUE);

	// Show error on standard out if running from command line
	//--------------------------------------------------------

	if (preg_match('/cli/', php_sapi_name()))
		echo "Fatal - uncaught exception: " . $exception->getMessage() . "\n";
	else
		echo "<div>Ooooops: " . $exception->getMessage() . "</div>";
}

// Set error and exception handlers
//---------------------------------

set_error_handler("_clearos_error_handler");
set_exception_handler("_clearos_exception_handler");

// vim: syntax=php ts=4

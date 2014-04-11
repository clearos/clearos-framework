<?php

/**
 * ClearOS bootstrap process.
 *
 * The bootstrap process does two things:
 * - Loads the configuration
 * - Loads the core functions and helpers
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

require_once 'libraries/Config.php';

///////////////////////////////////////////////////////////////////////////////
// C O N F I G U R A T I O N
///////////////////////////////////////////////////////////////////////////////

getenv('CLEAROS_BOOTSTRAP'); // Pull in environment variable

// Quick way to enable debug
//--------------------------

if (file_exists('/tmp/webconfig.debug')) {
    Config::$debug_mode = TRUE;
    Config::$debug_log = '/tmp/webconfig.log';
}

// If the CLEAROS_CONFIG enviroment variable is set, load the values.
//------------------------------------------------------------------------

if (isset($_SERVER['CLEAROS_CONFIG']) && file_exists($_SERVER['CLEAROS_CONFIG']))
    require_once($_SERVER['CLEAROS_CONFIG']);

// Change relative paths to full paths
//------------------------------------

if (!empty(Config::$apps_paths)) {
    $home_dir = preg_replace('/\.clearos/', '', getenv('CLEAROS_CONFIG')); // A bit dirty
    $real_paths = array();

    foreach (Config::$apps_paths as $path)
        $real_paths[] = (preg_match('/^\//', $path)) ? $path : $home_dir . '/' . $path;

    Config::$apps_paths = $real_paths;
}

if (!empty(Config::$theme_paths)) {
    $home_dir = preg_replace('/\.clearos/', '', getenv('CLEAROS_CONFIG')); // A bit dirty
    $theme_real_paths = array();

    foreach (Config::$theme_paths as $path)
        $real_paths[] = (preg_match('/^\//', $path)) ? $path : $home_dir . '/' . $path;

    Config::$theme_paths = $real_paths;
}

// Add default paths
//------------------

if (!in_array('/usr/clearos/apps', Config::$apps_paths))
    Config::$apps_paths[] = '/usr/clearos/apps';

if (!empty(Config::$theme_paths) && !in_array('/usr/clearos/themes', Config::$theme_paths))
    Config::$theme_paths[] = '/usr/clearos/apps';

// Translations in developer mode 
//-------------------------------

if (file_exists('/etc/clearos/devel.d/translator_mode'))
    array_unshift(Config::$apps_paths, '/var/clearos/base/translations');

///////////////////////////////////////////////////////////////////////////////
// C O R E  F U N C T I O N S  A N D  H E L P E R S
///////////////////////////////////////////////////////////////////////////////

require_once Config::get_framework_path() . '/shared/globals.php';

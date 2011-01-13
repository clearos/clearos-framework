<?php

/**
 * ClearOS bootstrap process.
 *
 * The bootstrap process does two things:
 * - Loads the configuration
 * - Loads the core functions and helpers
 *
 * @category  ClearOS
 * @package   Framework
 * @author    ClearFoundation <developer@clearfoundation.com>
 * @copyright 2010-2011 ClearFoundation
 * @license   http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link      http://www.clearfoundation.com/docs/developer/framework/
 */

//////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//  
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

use \clearos\framework\Config as Config;

require_once 'Config.php';

///////////////////////////////////////////////////////////////////////////////
// C O N F I G U R A T I O N
///////////////////////////////////////////////////////////////////////////////

// If the CLEAROS_BOOTSTRAP environment variable is defined, then the core
// system is running in development mode.  Provide some intelligent
// configuration defaults.
//------------------------------------------------------------------------

if (isset($_ENV['CLEAROS_BOOTSTRAP'])) {

    // Find a unique debug name to avoid log collisions.  Base on path.
    //----------------------------------------------------------------

    $debugname = preg_replace('/\/webconfig\/.*/', '', dirname(__FILE__));

    // Remove "home" and "clearos" paths if they exist
    $debugname = preg_replace('/(home)|(clearos)/', '', $debugname);

    // Remove slashes
    $debugname = preg_replace('/\//', '', $debugname);

    // Versioning for development and testing
    //----------------------------------------------------------------
    // FIXME: auto-detect default version based on dirname(__FILE__);

    Config::$clearos_devel_versions['app']['default'] = 'trunk';
    Config::$clearos_devel_versions['theme']['default'] = 'trunk';
    Config::$clearos_devel_versions['framework'] = 'trunk';

    // Paths
    //----------------------------------------------------------------
    // FIXME: trunk should not be hardcoded.

    $basedir = preg_replace('/\/framework\/.*/', '', dirname(__FILE__));

    Config::$apps_path = $basedir . '/apps';
    Config::$framework_path = $basedir . '/framework';
    Config::$htdocs_path = $basedir . '/framework/trunk/htdocs';
    Config::$themes_path = $basedir . '/themes';

    // Debug mode
    //----------------------------------------------------------------

    Config::$debug_mode = TRUE;
    Config::$debug_log = '/tmp/clearos_framework_' . $debugname . '_log';
}

// If the CLEAROS_CONFIG enviroment variable is set, load the values.
//------------------------------------------------------------------------

/*
if (!empty($_ENV['CLEAROS_CONFIG']))
    require_once($_ENV['CLEAROS_CONFIG']);
*/

///////////////////////////////////////////////////////////////////////////////
// C O R E  F U N C T I O N S  A N D  H E L P E R S
///////////////////////////////////////////////////////////////////////////////

if (!empty(Config::$clearos_devel_versions['framework']))
    $version = Config::$clearos_devel_versions['framework'];
else
    $version = '';

require_once Config::$framework_path . '/' . $version . '/shared/Core.php';

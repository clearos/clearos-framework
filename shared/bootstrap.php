<?php

//////////////////////////////////////////////////////////////////////////////
//
// Copyright 2010 ClearFoundation
//
///////////////////////////////////////////////////////////////////////////////

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

/**
 * ClearOS bootstrap process.
 *
 * The bootstrap process does two things:
 * - Loads the configuration
 * - Loads the core functions and helpers
 *
 * @package Framework
 * @author {@link http://www.foundation.com/ ClearFoundation}
 * @license http://www.gnu.org/copyleft/lgpl.html GNU General Public License version 3 or later
 * @copyright Copyright 2010 ClearFoundation
 */

///////////////////////////////////////////////////////////////////////////////
// C O N F I G U R A T I O N
///////////////////////////////////////////////////////////////////////////////

// Load the default configuration
//-------------------------------

require_once('ClearOsConfig.php');

// If the CLEAROS_BOOTSTRAP environment variable is defined, then the core
// system is running in development mode.  Provide some intelligent
// configuration defaults.
//------------------------------------------------------------------------

if (isset($_ENV['CLEAROS_BOOTSTRAP'])) {
	// Paths
	$basedir = preg_replace('/\/framework\/.*/', '', dirname(__FILE__));
	ClearOsConfig::$apps_path = $basedir . '/apps';
	ClearOsConfig::$framework_path = $basedir . '/framework/trunk';
	ClearOsConfig::$htdocs_path = $basedir . '/framework/trunk/htdocs';
	ClearOsConfig::$themes_path = $basedir . '/themes';

	// Debug mode
	ClearOsConfig::$debug_mode = TRUE;
	ClearOsConfig::$debug_log = '/tmp/clearos_framework_' . $_ENV['USER'] . '_log';

	// Versioning for development and testing
// FIXME: auto-detect default version based on dirname(__FILE__);
	ClearOsConfig::$clearos_devel_versions['app']['default'] = 'trunk';
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

if (!empty(ClearOsConfig::$clearos_devel_versions['framework']))
    $version = ClearOsConfig::$clearos_devel_versions['framework'];
else
    $version = '';

require_once(ClearOsConfig::$framework_path . '/' . $version . '/shared/ClearOsCore.php');

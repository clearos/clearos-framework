<?php

///////////////////////////////////////////////////////////////////////////////
//
// Copyright 2010 ClearFoundation
//
///////////////////////////////////////////////////////////////////////////////

/**
 * ClearOS framework configuration
 *
 * @package Framework
 * @author {@link http://www.foundation.com/ ClearFoundation}
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @copyright Copyright 2010, ClearFoundation
 */

class ClearOsFramework {
	// Paths
	public static $apps_path = '/usr/clearos/apps';
	public static $framework_path = '/usr/clearos/framework';
	public static $htdocs_path = '/usr/clearos/framework/htdocs';
	public static $themes_path = '/usr/clearos/themes';

	// Debug mode
	public static $debug_mode = TRUE;
	public static $debug_log_path = '/var/log/webconfig/';

	// Development versioning
	public static $clearos_devel_versions = array();
}
// FIXME: review the path below and make sure it's easy to enable debug mode
/*
if (!defined('CLEAROS_DEBUG_MODE'))
	define('CLEAROS_DEBUG_MODE', 'off');

if (!defined('CLEAROS_DEBUG_LOG'))
	define('CLEAROS_DEBUG_LOG', '/var/log/webconfig/debug.log');
*/
// FIXME: merge with CodeIgniter environment
// FIXME: COMMON_CORE_DIR remove references to this
define('COMMON_CORE_DIR', ClearOsFramework::$framework_path . '/application/libraries');

// FIXME: change name and path -- added to webconfig-httpd
define("COMMON_TEMP_DIR", "/usr/webconfig/tmp");

// FIXME - transition only... remove
define('COMMON_DEBUG_MODE', true);

// For developer mode, you can pull in a custom environment
//---------------------------------------------------------

if (isset($_ENV['CLEAROS_CONFIG'])) {
	if (!file_exists($_ENV['CLEAROS_CONFIG']))
		die('You have defined a CLEAROS_CONFIG file, but the file is missing: ' . $_ENV['CLEAROS_CONFIG'] . '\n');
	else
		require_once($_ENV['CLEAROS_CONFIG']);
}

// Pull in global functions and error handlers
//--------------------------------------------

require_once(ClearOsFramework::$framework_path . '/shared/ClearOsCore.php');

// vim: syntax=php ts=4

<?php

///////////////////////////////////////////////////////////////////////////////
//
// Copyright 2010 ClearFoundation
//
///////////////////////////////////////////////////////////////////////////////
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
 * ClearOS framework configuration.
 *
 * @package Framework
 * @author {@link http://www.foundation.com/ ClearFoundation}
 * @license http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @copyright Copyright 2010 ClearFoundation
 */

/**
 * ClearOS framework configuration class.
 *
 * @package Framework
 * @author {@link http://www.foundation.com/ ClearFoundation}
 * @license http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @copyright Copyright 2010 ClearFoundation
 */

class ClearOsConfig {

	//-----------------------------------------------------------------------
	// P A T H S
	//-----------------------------------------------------------------------

	/**
	 * @var string base path for apps
	 */

	public static $apps_path = '/usr/clearos/apps';

	/**
	 * @var string base path for the framework
	 */

	public static $framework_path = '/usr/clearos/framework';

	/**
	 * @var string base path for the web server document root
	 */

	public static $htdocs_path = '/usr/clearos/framework/htdocs';

	/**
	 * @var string base path for themes
	 */

	public static $themes_path = '/usr/clearos/themes';

	//-----------------------------------------------------------------------
	// D E V E L O P E R
	//-----------------------------------------------------------------------

	/**
	 * @var boolean debug mode flag
	 */

	public static $debug_mode = TRUE;

	/**
	 * @var string debug log path
	 */

	public static $debug_log = '/var/log/webconfig/framework_log';

	/**
	 * @var array version handler for app developers
	 */

	public static $clearos_devel_versions = array();
}

// vim: syntax=php ts=4

<?php

/**
 * ClearOS framework configuration.
 *
 * @category   Framework
 * @package    Shared
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/framework/
 */

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

///////////////////////////////////////////////////////////////////////////////
// N A M E S P A C E
///////////////////////////////////////////////////////////////////////////////

namespace clearos\framework;

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

use \clearos\framework\Logger as Logger;

require_once 'Logger.php';

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * ClearOS framework configuration.
 *
 * @category   Framework
 * @package    Shared
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/framework/
 */

class Config
{
    //-----------------------------------------------------------------------
    // V A R I A B L E S
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

    /**
     * @var boolean debug mode flag
     */

    public static $debug_mode = FALSE;

    /**
     * @var string debug log path
     */

    public static $debug_log = '/var/log/webconfig/framework_log';

    /**
     * @var array version handler for app developers
     */

    public static $clearos_devel_versions = array();

    ///////////////////////////////////////////////////////////////////////////////
    // M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Config constructor.
     */

    public function __construct()
    {
        Logger::profile(__METHOD__, __LINE__);
    }

    /**
     * Returns the app URL.
     *
     * @param string $app app name
     *
     * @return string app URL
     */

    public static function get_app_url($app)
    {
        Logger::profile(__METHOD__, __LINE__);

        if (isset(Config::$clearos_devel_versions['app'][$app]))
            $app_version = Config::$clearos_devel_versions['app'][$app] . '/';
        else if (isset(Config::$clearos_devel_versions['app']['default']))
            $app_version = Config::$clearos_devel_versions['app']['default'] . '/';
        else
            $app_version = "";

        // FIXME: cleanup hard coded value below
        return '/' . $app . '/' . $app_version . 'htdocs';
    }

    /**
     * Returns the theme path.
     *
     * @param string $theme theme name
     *
     * @return string theme path
     */

    public static function get_theme_path($theme)
    {
        Logger::profile(__METHOD__, __LINE__);

        if (isset(Config::$clearos_devel_versions['theme'][$theme]))
            $theme_version = '/' . Config::$clearos_devel_versions['theme'][$theme];
        else if (isset(Config::$clearos_devel_versions['theme']['default']))
            $theme_version = '/' . Config::$clearos_devel_versions['theme']['default'];
        else
            $theme_version = "";

        return Config::$themes_path . '/' . $theme . $theme_version;
    }

    /**
     * Returns the theme URL.
     *
     * @param string $theme theme name
     *
     * @return string theme URL
     */

    public static function get_theme_url($theme)
    {
        Logger::profile(__METHOD__, __LINE__);

        if (isset(Config::$clearos_devel_versions['theme'][$theme]))
            $theme_version = '/' . Config::$clearos_devel_versions['theme'][$theme];
        else if (isset(Config::$clearos_devel_versions['theme']['default']))
            $theme_version = '/' . Config::$clearos_devel_versions['theme']['default'];
        else
            $theme_version = "";

        // FIXME: cleanup hard coded value below
        // FIXME: merge common blocks of code
        return "/themes/" . $theme . $theme_version;
    }
}

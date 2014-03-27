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
use \clearos\framework\Apps as Apps;

require_once 'Logger.php';
require_once 'Apps.php';

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
     * @var array apps paths
     */

    public static $apps_path = '/usr/clearos/apps';
    public static $apps_paths = array();

    /**
     * @var string base path for the framework
     */

    public static $framework_path = '/usr/clearos/framework';

    /**
     * @var string base path for themes
     */

    public static $theme_path = '/usr/clearos/themes';
    public static $theme_paths = array();

    /**
     * @var boolean debug mode flag
     */

    public static $debug_mode = FALSE;

    /**
     * @var string debug log path
     */

    public static $debug_log = '/tmp/framework_log';

    /**
     * @var array scm path handler
     */

    public static $scm_subpaths = array('trunk', '');

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
     * Returns the app base path.
     *
     * @param string $app app name
     *
     * @return string app base path
     */

    public static function get_app_base($app)
    {
        // Logger::profile here is too verbose, so skip it

        $version_paths = array('trunk', '');

        foreach (Config::get_apps_paths() as $path) {
            foreach ($version_paths as $version_path) {
                $base = $path . '/' . $app . '/' . $version_path;

                if (is_dir("$base/deploy"))
                    return $base;
            }
        }
    }

    /**
     * Returns the app root path.
     *
     * @param string $app app name
     *
     * @return string app root path
     */

    public static function get_app_root($app)
    {
        // Logging is verbose, don't bother

        // An app can not only be in /usr/clearos/apps, but also in development
        // directories as well.  This function returns the web server root path
        // used for aliasing in the Apache configlet, e.g.
        // - /usr/clearos/apps -> /approot
        // - /home/test/my_playground/apps -> /my_plaground/approot

        $base_path = Config::get_app_base($app);

        // Return default /approot for live apps ASAP

        if (preg_match('/\/usr\/clearos\/apps/', $base_path))
            return '/approot';

        // Loop through the configured apps paths configured by the 
        // developer looking for the app root path.

        foreach (Config::get_apps_paths() as $path) {
            $app_root = Config::_get_app_root_alias($path, $base_path);

            if (! empty($app_root))
                return $app_root;
        }
    }

    /**
     * Returns the app root mappings.
     *
     * @param string $path path
     *
     * @return string approot mapping
     */

    public static function get_app_root_mappings()
    {
        Logger::profile(__METHOD__, __LINE__);

        // This generates a list of app root mappings for the
        // Apache configlet file used in develoment mode.
        // See get_app_root for details.

        $apps = Apps::get_list(FALSE);
        $apps_paths = Config::get_apps_paths();
        $app_roots = array();

        foreach ($apps as $app) {
            $base_path = Config::get_app_base($app);

            foreach ($apps_paths as $path) {
                $path = preg_replace('/\/\//', '/', $path); // Remove double slashes
                $app_root = Config::_get_app_root_alias($path, $base_path);

                if (!empty($app_root) && ($path != '/usr/clearos/apps'))
                    $app_roots[$app_root] = $path;
            }
        }

        $app_roots = array_unique($app_roots);

        return $app_roots;
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

        $approot = Config::get_app_root($app);

        $version_paths = array('trunk', '');

        foreach (Config::get_apps_paths() as $path) {
            foreach ($version_paths as $version_path) {
                $base = $path . '/' . $app . '/' . $version_path;

                if (is_dir("$base/deploy"))
                    return $approot . '/' . $app . '/' . $version_path . '/htdocs';
            }
        }
    }

    /**
     * Returns app paths.
     *
     * @return array app paths
     */

    public static function get_apps_paths()
    {
        if (empty(Config::$apps_paths))
            return array(Config::$apps_path);
        else
            return Config::$apps_paths;
    }

    /**
     * Returns the framework path.
     *
     * @return string framework path
     */

    public static function get_framework_path()
    {
        $bootstrap = getenv('CLEAROS_BOOTSTRAP');

        if (!empty($bootstrap)) {
            $framework_path = preg_replace('/shared$/', '', $bootstrap);
            return $framework_path;
        }

        $version_paths = array('trunk', '');

        foreach ($version_paths as $version_path) {
            if (file_exists(Config::$framework_path . "/$version_path/shared/globals.php")) {
                return Config::$framework_path . "/$version_path";
            }
        }
    }

    /**
     * Returns the report driver name.
     *
     * @return string driver name
     */

    public static function get_reports_driver()
    {
        Logger::profile(__METHOD__, __LINE__);

        if (clearos_app_installed('professional_reports'))
            return 'professional_reports';
        else
            return 'home_reports';
    }

    /**
     * Returns the summary driver name.
     *
     * @return string driver name
     */

    public static function get_summary_driver()
    {
        Logger::profile(__METHOD__, __LINE__);

        // TODO: add support for other drivers
        return 'marketplace';
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

        if (empty(Config::$theme_paths))
            return Config::$theme_path . '/' . $theme;

        $version_paths = array('trunk', '');

        foreach (Config::$theme_paths as $path) {
            foreach ($version_paths as $version_path) {
                if (file_exists("$path/$theme/$version_path/core/page.php"))
                    return "$path/$theme/$version_path";
            }
        }
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

        // Return default
        if (empty(Config::$theme_paths))
            return "/themes/$theme";

        $version_paths = array('trunk', '');

        foreach (Config::$theme_paths as $path) {
            foreach ($version_paths as $version_path) {
                $base_path = $path . '/' . $theme . '/' . $version_path;

                if (file_exists("$base_path/core/page.php")) {
                    $alias = preg_replace('/\/webconfig\/themes\/.*/', '', $base_path);
                    $alias = preg_replace('/\/themes\/.*/', '', $alias);
                    $alias = preg_replace('/.*\//', '', $alias);

                    $url = '/' . $alias . "/themes/$theme/$version_path";

                    // Clean for case of empty alias and version path
                    $url = preg_replace(array('|^//|', '|/$|'), array('/', ''), $url);

                    return $url;
                }
            }
        }
    }

    /**
     * Returns approot mapping for given path.
     *
     * @param string $path path
     *
     * @return string approot mapping
     */

    private static function _get_app_root_alias($path, $base_path)
    {
        // For development apps we need to create an Apache alias to
        // that maps into the htdocs directory.  Things like Javascript
        // and logos should not go through CodeIgniter engine.
        //
        // For the aliases, we use something readable instead of a hash,
        // so /home/devuser/some_place/apps is aliased to /some_place_apps

        $path = preg_replace('/\/\//', '/', $path); // Remove double slashes
        $base_path = preg_replace('/\/\//', '/', $base_path); // Remove double slashes
        $preg_path = preg_quote($path, '/');

        if (!(preg_match("/$preg_path/", $base_path)))
            return '';

        // Legacy support for SVN (e.g. ~clearos/webconfig/apps aliased to /clearos)
        if (preg_match('/\/clearos\/webconfig\/apps$/', $path)) {
            $base_path = 'clearos';
        } else if (preg_match('/\/clearcenter\/webconfig\/apps$/', $path)) {
            $base_path = 'clearcenter';
        } else {
            // Path munging - /home/devuser/some_place/apps/some_app
            // i) remove home directory portion
            $home_dir = preg_replace('/\.clearos/', '', getenv('CLEAROS_CONFIG')); // Home directory
            $base_path = preg_replace('/' . preg_quote($home_dir, '/') . '/', '', $base_path);

            // ii) remove trailing app name
            $base_path = preg_replace('/\/$/', '', $base_path);
            $base_path = preg_replace('/\/\w*$/', '', $base_path);

            // iii) convert remaining slashes in path (typically some_place/apps) to underscores
            $base_path = preg_replace('/\//', '_', $base_path);
        }

        return '/' . $base_path . '/approot';
    }
}

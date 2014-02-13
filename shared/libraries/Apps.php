<?php

/**
 * ClearOS apps information.
 *
 * @category   framework
 * @package    shared
 * @subpackage libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2013 ClearFoundation
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
use \clearos\framework\Config as Config;

require_once 'Logger.php';
require_once 'Config.php';

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * ClearOS apps information.
 *
 * @category   framework
 * @package    shared
 * @subpackage libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2013 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/framework/
 */

class Apps
{
    ///////////////////////////////////////////////////////////////////////////////
    // M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Apps constructor.
     */

    public function __construct()
    {
        Logger::profile(__METHOD__, __LINE__);
    }

    /**
     * Returns the apps list.
     *
     * @return string apps list
     */

    public static function get_list($detailed = TRUE)
    {
        // Define menu order preferences
        //------------------------------

        if ($detailed) {
            $primary_order = array(
                lang('base_category_cloud')  => '010',
                lang('base_category_gateway') => '020',
                lang('base_category_server')  => '030',
                lang('base_category_network') => '040',
                lang('base_category_system')  => '050',
                lang('base_category_reports')  => '060',
                lang('base_category_my_account')  => '070',
                lang('base_category_spotlight')  => '080',
            );

            $secondary_order = array(
                lang('base_subcategory_settings') => '999',
                lang('base_subcategory_accounts') => '020',
            );
        }

        // Define "base apps" (intalled by default)
        //-----------------------------------------

        // FIXME - this list should come from a default config file or installer?
        $core_app_list = array(
            'accounts',
            'account_synchronization',
            'base',
            'clearcenter',
            'configuration_backup',
            'dashboard',
            'date',
            'dhcp',
            'dns',
            'graphical_console',
            'groups',
            'incoming_firewall',
            'language',
            'mail_notification',
            'marketplace',
            'network',
            'process_viewer',
            'registration',
            'software_updates',
            'ssh_server',
            'user_profile',
            'users'
        );

        // Grab raw data from all the deploy/info.php files
        //-------------------------------------------------

        $apps_list = array();

        foreach (Config::get_apps_paths() as $path) {
            $raw_list = scandir($path);

            foreach ($raw_list as $app_name) {
                if (! preg_match('/^\./', $app_name)) {
                    $info_file = clearos_app_base($app_name) . '/deploy/info.php';

                    if (file_exists($info_file)) {

                        // For the non-detailed list, just return a list of apps
                        if (!$detailed) {
                            $apps_list[] = $app_name;
                            continue;
                        }

                        // Load the "$app" information
                        $app = array();
                        clearos_load_language($app_name);
                        include $info_file;

                        // Skip apps without a defined basename
                        if (!isset($app['basename']))
                            continue;

                        // Skip apps without menus
                        if (isset($app['menu_enabled']) && ($app['menu_enabled']) === FALSE)
                            continue;

                        // If only the -core package is installed, skip it
                        $htdocs_dir = Config::get_app_base($app_name) . '/htdocs';
                        if (!is_dir($htdocs_dir))
                            continue;

                        $apps_list[$app_name]['name'] = $app['name'];
                        $apps_list[$app_name]['basename'] = $app['basename'];
                        $apps_list[$app_name]['menu_enabled'] = (isset($app['menu_enabled'])) ? $app['menu_enabled'] : TRUE;
                        $apps_list[$app_name]['user_access'] = (isset($app['user_access'])) ? $app['user_access'] : FALSE;
                        $apps_list[$app_name]['category'] = $app['category'];
                        $apps_list[$app_name]['subcategory'] = $app['subcategory'];
                        $apps_list[$app_name]['is_core'] = in_array($app['basename'], $core_app_list) ? TRUE : FALSE;

                        // Add timestamp
                        $stat = stat($info_file);
                        $apps_list[$app_name]['modified'] = $stat['ctime'];
                        $apps_list[$app_name]['installed'] = filemtime($info_file);

                        // Add sort order
                        $primary_sort = empty($primary_order[$app['category']]) ? '500' : $primary_order[$app['category']];
                        $secondary_sort = empty($secondary_order[$app['subcategory']]) ? '500' . $app['subcategory'] : $secondary_order[$app['subcategory']];
                        $page_sort = empty($app['priority']) ? '500' : $app['priority'];

                        $apps_list[$app_name]['priority'] = $primary_sort . '.' . $secondary_sort . '.' . $page_sort . '.' . $app['name'];
                    }
                }
            }
        }

        return $apps_list;
    }
}

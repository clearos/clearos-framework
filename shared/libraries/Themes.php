<?php

/**
 * ClearOS theme information.
 *
 * @category   framework
 * @package    shared
 * @subpackage libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2014 ClearFoundation
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
 * ClearOS theme information.
 *
 * @category   framework
 * @package    shared
 * @subpackage libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2014 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/framework/
 */

class Themes
{
    ///////////////////////////////////////////////////////////////////////////////
    // M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Themes constructor.
     */

    public function __construct()
    {
        Logger::profile(__METHOD__, __LINE__);
    }

    /**
     * Returns the theme list.
     *
     * @return string theme list
     */

    public static function get_list()
    {
        $theme_list = array();

        foreach (Config::get_theme_paths() as $path) {
            $raw_list = scandir($path);

            foreach ($raw_list as $theme_name) {
                if (empty($theme_list[$theme_name]) && file_exists($path . '/' . $theme_name . '/deploy/info.php')) {
                    unset($package);
                    include $path . '/' . $theme_name . '/deploy/info.php';

                    $theme_list[$theme_name] = $package;
                }
            }
        }

        return $theme_list;
    }
}

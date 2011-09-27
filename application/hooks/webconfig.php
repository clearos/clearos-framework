<?php

/**
 * ClearOS webconfig session handling.
 *
 * The session handling is done through a CodeIngiter hook.
 *
 * @category   Framework
 * @package    Application
 * @subpackage Hooks
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/
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
// B O O T S T R A P
///////////////////////////////////////////////////////////////////////////////

$bootstrap = getenv('CLEAROS_BOOTSTRAP') ? getenv('CLEAROS_BOOTSTRAP') : '/usr/clearos/framework/shared';
require_once $bootstrap . '/bootstrap.php';

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

use \clearos\framework\Logger as Logger;

///////////////////////////////////////////////////////////////////////////////
// C A C H E
///////////////////////////////////////////////////////////////////////////////

function webconfig_cache()
{
    Logger::profile_framework(__METHOD__, __LINE__, 'Webconfig Cache Hook');

    // Language setting cache
    //----------------------- 

    clearstatcache();

    $lang_cache_time = 0;

    if (file_exists(CLEAROS_TEMP_DIR . '/language_cache.php')) {
        $stat = stat(CLEAROS_TEMP_DIR . '/language_cache.php');
        $lang_cache_time = $stat['ctime'];
    }

    $stat = stat('/etc/sysconfig/i18n');
    $lang_actual_time = $stat['ctime'];

    if ($lang_cache_time < $lang_actual_time) {
        $raw_contents =
        $lines = preg_split("/\n/", file_get_contents('/etc/sysconfig/i18n'));

        foreach ($lines as $line) {
            if (preg_match('/^LANG=/', $line)) {
                $lang = preg_replace('/^LANG=/', '', $line);
                $lang = preg_replace('/\..*/', '', $lang);
                $lang = preg_replace('/["\']/', '', $lang);
            }
        } 

        // Concatenation is to avoid breaking syntax highlighting
        $contents = "<?php \$language = '$lang'; ?" . ">\n";
        file_put_contents(CLEAROS_TEMP_DIR . '/language_cache.php', $contents);
        chmod(CLEAROS_TEMP_DIR . '/language_cache.php', '0644');
    }
}

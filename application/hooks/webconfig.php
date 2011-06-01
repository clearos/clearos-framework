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

// Dependencies are loaded below since it is only necessary on session start.

use \clearos\framework\Logger as Logger;
use \clearos\apps\base\OS as OS;
use \clearos\apps\base\Product as Product;
use \clearos\apps\base\Webconfig as Webconfig;
use \clearos\apps\language\Locale as Locale;
use \clearos\apps\network\Hostname as Hostname;

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
    }
}

///////////////////////////////////////////////////////////////////////////////
// S E S S I O N
///////////////////////////////////////////////////////////////////////////////

function webconfig_session()
{
    Logger::profile_framework(__METHOD__, __LINE__, 'Webconfig Session Hook');

    $framework =& get_instance();

    if ($framework->session->userdata('session_started') == '1')
        return;

    ///////////////////////////////////////////////////////////////////////////
    // D E P E N D E N C I E S . . . A G A I N
    ///////////////////////////////////////////////////////////////////////////

    Logger::profile_framework(__METHOD__, __LINE__, 'Loading base session data');

    // Hostname
    //---------

    $session['hostname'] = '';

    if (clearos_load_library('network/Hostname')) {
        try {
            $hostname = new Hostname();
            $session['hostname'] = $hostname->Get();
        } catch (Exception $e) {
            // Use default
        }
    }

    // Check registration
    //-------------------

    // FIXME
    $session['registered'] = FALSE;
    $session['sdn_redirect'] = 'https://secure.clearcenter.com/redirect';
    $session['online_help'] = 'https://secure.clearcenter.com/redirect/userguide';

    if (file_exists(COMMON_CORE_DIR . "/api/Register.php")) {
        require_once(COMMON_CORE_DIR . "/api/Register.php");

        try {
            $register = new Register();
            $session['registered'] = $register->GetStatus();
        } catch (Exception $e) {
            // Use default
        }
    }

    // Language
    //---------

    $session['locale'] = 'en_US';
    $session['encoding'] = 'utf-8';
    $session['textdir'] = 'LTR';

    if (clearos_load_library('language/Locale')) {
        try {
            $locale = new Locale();
            $session['locale'] = $locale->get_language_code();
            $session['textdir'] = $locale->get_text_direction();
            $session['encoding'] = $locale->get_encoding();
        } catch (Exception $e) {
            // Use default
        }
    }

    setlocale(LC_ALL, $session['locale']);

    // Product Info
    //-------------

    $session['osname'] = 'Linux';
    $session['osversion'] = '2.6';
    $session['redirect'] = '';

    if (clearos_load_library('base/Product')) {
        try {
            $product = new Product();
            $session['osname'] = $product->get_name();
            $session['osversion'] = $product->get_version();
        } catch (Exception $e) {
            // Use default
        }
    } else if (clearos_load_library('base/OS')) {
        try {
            $os = new OS();
            $session['osname'] = $os->get_name();
            $session['osversion'] = $os->get_version();
        } catch (Exception $e) {
            // Use default
        }
    }

    // Theme
    //------

    $session['theme'] = 'clearos6x';
    $session['theme_mode'] = 'normal';

    if (clearos_load_library('base/Webconfig')) {
        $webconfig = new Webconfig();

        try {
            $session['theme'] = $webconfig->get_theme();
            $session['theme_mode'] = $webconfig->get_theme_mode();
        } catch (Exception $e) {
            // Use default
        }
    }

    // Set the session
    //----------------

    $session['session_started'] = TRUE;

    $framework->session->set_userdata($session);
}

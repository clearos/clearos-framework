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

// FIXME - consider moving boostrap and dependencies since sessions only need
// to load one time at the start of the process

use \clearos\framework\Logger as Logger;
use \clearos\apps\base\Webconfig as Webconfig;

clearos_load_library('base/Webconfig');

///////////////////////////////////////////////////////////////////////////////
// S E S S I O N
///////////////////////////////////////////////////////////////////////////////

function webconfig_session()
{
    Logger::profile_framework(__METHOD__, __LINE__, 'Webconfig Session Hook');

    $framework =& get_instance();

    if ($framework->session->userdata('session_started') == '1')
        return;

    Logger::profile_framework(__METHOD__, __LINE__, 'Loading base session data');

    $webconfig = new Webconfig();

    // Hostname
    //---------

    $session['hostname'] = '';

    if (file_exists(COMMON_CORE_DIR . "/api/Hostname.php")) {
        require_once(COMMON_CORE_DIR . "/api/Hostname.php");

        try {
            $hostname = new Hostname();
            $session['hostname'] = $hostname->Get();
        } catch (Exception $e) {
            // Use default
        }
    }

    // Check registration
    //-------------------

    $session['registered'] = FALSE;

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
    $session['charset'] = 'utf-8';
    $session['textdir'] = 'LTR';

    if (file_exists(COMMON_CORE_DIR . "/api/Locale.php")) {
        require_once(COMMON_CORE_DIR . "/api/Locale.php");

        try {
            $locale = new Locale();
            $session['locale'] = $locale->GetLanguageCode();
            $session['charset'] = $locale->GetCharacterSet();
            $session['textdir'] = $locale->GetTextDirection();
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

    if (file_exists(COMMON_CORE_DIR . "/api/Product.php")) {
        require_once(COMMON_CORE_DIR . "/api/Product.php");

        try {
            $product = new Product();
            $session['osname'] = $product->GetName();
            $session['osversion'] = $product->GetVersion();
            $session['redirect'] = $product->GetRedirectUrl() . "/" . preg_replace("/ /", "_", $osname) . "/" . $osversion;
        } catch (Exception $e) {
            // Use default
        }
    } else if (file_exists(COMMON_CORE_DIR . "/api/Os.php")) {
        require_once(COMMON_CORE_DIR . "/api/Os.php");

        try {
            $os = new Os();
            $osname = $os->GetName();
            $osversion = $os->GetVersion();
        } catch (Exception $e) {
            // Use default
        }
    }

    // Hostkey
    //--------

    // FIXME: avoid this
    $session['hostkey'] = "hostkey";

    if (file_exists(COMMON_CORE_DIR . "/api/Suva.php")) {
        require_once(COMMON_CORE_DIR . "/api/Suva.php");

        try {
            $suva = new Suva();
            $session['hostkey'] = $suva->GetHostkey();
        } catch (Exception $e) {
            // Use default
        }
    }

    // Theme
    //------

    $session['theme'] = "clearos6x";
    $session['theme_mode'] = 'normal';

    if (file_exists(COMMON_CORE_DIR . "/api/Webconfig.php")) {
        require_once(COMMON_CORE_DIR . "/api/Webconfig.php");

        try {
            $session['theme'] = $webconfig->GetTemplate();
            $session['theme_mode'] = 'normal';
        } catch (Exception $e) {
            // Use default
        }
    }

    // Other
    //------

    // FIXME - messy?
    $session['sdn_redirect'] = 'https://secure.clearcenter.com/redirect';
    $session['online_help'] = 'https://secure.clearcenter.com/redirect/userguide';
    $session['session_started'] = TRUE;

    // Set the session
    //----------------

    $framework->session->set_userdata($session);
}

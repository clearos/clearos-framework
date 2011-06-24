<?php

/**
 * Webconfig login session handling.
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
// C L A S S
///////////////////////////////////////////////////////////////////////////////

class MY_Login_Session
{
    ///////////////////////////////////////////////////////////////////////////////
    // M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Login session constructor.
     */

    public function __construct()
    {
        Logger::profile_framework(__METHOD__, __LINE__, 'Login Session Class Initialized');
    }

    /**
     * Reloads translations for given app.
     *
     * When changing the language, we need to reload language files in order
     * to display the right language!  This little helper does the trick.
     *
     * @return void
     */

    public function reload_language($app)
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        $framework =& get_instance();

        $loaded = $framework->session->CI->lang->is_loaded;

        $new_loaded = array();
        $app_file = $app . '_lang';

        foreach ($loaded as $loaded_lang) {
            if ($loaded_lang !== $app_file)
                $new_loaded[] = $loaded_lang;
        }

        $framework->session->CI->lang->is_loaded = $new_loaded;

        $framework->lang->load($app);
    }

    /**
     * Changes session locale.
     *
     * @return void
     */

    public function set_locale($code)
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        $framework =& get_instance();

        // Set session
        $framework->session->set_userdata('lang_code', $code);

        // FIXME: text direction and character set need to be updated too

        // Clear the cache when changing the session language
        $framework->page->clear_cache();
    }

    /**
     * Starts login session.
     *
     * @return void
     */

    public function start()
    {
        Logger::profile_framework(__METHOD__, __LINE__);

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

        // The language code can left alone if it is already set.

        if (! $framework->session->userdata('lang_code')) {
            $session['lang_code'] = 'en_US';
            $session['encoding'] = 'utf-8';
            $session['textdir'] = 'LTR';

            if (clearos_load_library('language/Locale')) {
                try {
                    $locale = new Locale();
                    $session['lang_code'] = $locale->get_language_code();
                    $session['textdir'] = $locale->get_text_direction();
                    $session['encoding'] = $locale->get_encoding();
                } catch (Exception $e) {
                    // Use default
                }
            }

            setlocale(LC_ALL, $session['lang_code']);
        }

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
}

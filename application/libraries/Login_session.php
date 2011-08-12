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
use \clearos\apps\base\Authorization as Authorization;
use \clearos\apps\base\OS as OS;
use \clearos\apps\base\Product as Product;
use \clearos\apps\base\Webconfig as Webconfig;
use \clearos\apps\language\Locale as Locale;
use \clearos\apps\network\Hostname as Hostname;

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Webconfig login session handling.
 *
 * The security model is simple - all protected pages start with a call to
 * WebAuthenticate().  The function does one of three things:
 *
 *  - 1) Returns (quietly) on success
 *  - 2) Returns a "login failed" username/password web form
 *  - 3) Returns an "access denied" page if user is accessing an unauthorized page
 *
 * @category   Framework
 * @package    Application
 * @subpackage Hooks
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/
 */

class MY_Login_Session
{
    ///////////////////////////////////////////////////////////////////////////////
    // V A R I A B L E S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * @var object framework instance
     */

    protected $framework = NULL;

    ///////////////////////////////////////////////////////////////////////////////
    // M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Login session constructor.
     */

    public function __construct()
    {
        Logger::profile_framework(__METHOD__, __LINE__, 'Login Session Class Initialized');

        $this->framework =& get_instance();
    }

    /**
     * Authenticates user.
     *
     * @param string $username username
     * @param string $password password
     *
     * @return TRUE if authentication is successful
     * @throws Engine_Exception
     */

    public function authenticate($username, $password)
    {
        clearos_profile(__METHOD__, __LINE__);

        $is_valid = FALSE;

        if (clearos_load_library('base/Authorization')) {
            $authorization = new Authorization();

            if ($authorization->authenticate($username, $password))
                $is_valid = TRUE;
        }

        return $is_valid;
    }

    /**
     * Checks the page authorization.
     *
     * This method is called by CodeIgniter before any controller is loaded.
     *
     * @return void
     */

    public function check_acl()
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        $username = $this->framework->session->userdata('username');
        $logged_in = (bool)$this->framework->session->userdata('logged_in');

        // Return right away if access granted
        //------------------------------------

        if (! clearos_load_library('base/Authorization'))
            redirect('base/session/login');

        $authorization = new Authorization();

        if ($authorization->check_acl($username, $_SERVER['PHP_SELF']))
            return;

        // If logged in but denied access, point the user in the right direction.
        // If not logged in, send the user to the login page.
        //-----------------------------------------------------------------------

        if ($logged_in) {
            redirect('base/session/access_denied');
        } else {
            if (!($_SERVER['PHP_SELF'] === '/app/base/session/login')) {
                $post_redirect = base64_encode($_SERVER['PHP_SELF']);
                redirect('base/session/login/' . $post_redirect);
            }
        }
    }

    /**
     * Checks authentication.
     *
     * @return void
     */

    public function is_authenticated()
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        if ($this->framework->session->userdata('logged_in'))
            return TRUE;
        else
            return FALSE;
    }

    /**
     * Changes session locale.
     *
     * @param string $code language code
     *
     * @return void
     */

    public function set_language($code)
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        // Set session
        $this->framework->session->set_userdata('lang_code', $code);

        // FIXME: text direction and character set need to be updated too

        // Clear the cache when changing the session language
        $this->framework->page->clear_cache();
    }

    /**
     * Starts login session.
     *
     * @return void
     */

    public function start()
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        if ($this->framework->session->userdata('session_started') == '1')
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
                $session['hostname'] = $hostname->get();
            } catch (Exception $e) {
                // Use default
            }
        }

        // Check registration
        //-------------------

        // FIXME
        $session['registered'] = FALSE;

        // Language
        //---------

        // The language code can left alone if it is already set.

        if (! $this->framework->session->userdata('lang_code')) {
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

        $this->framework->session->set_userdata($session);
    }

    /**
     * Starts a login session.
     * 
     * @param string $username username
     *
     * @return void
     */

    public function start_authenticated($username)
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        $this->framework->session->set_userdata('logged_in', 'TRUE');
        $this->framework->session->set_userdata('username', $username);
        // FIXME: add user's full name
    }

    /**
     * Stops a login session.
     *
     * @return void
     */

    public function stop_authenticated()
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        $this->framework->session->unset_userdata('logged_in');
        $this->framework->session->unset_userdata('username');
        $this->framework->session->unset_userdata('session_started');
    }
    
    /**
     * Reloads translations for given app.
     *
     * When changing the language, we need to reload language files in order
     * to display the right language!  This little helper does the trick.
     *
     * @param string $app app name
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
}

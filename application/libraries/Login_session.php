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
use \clearos\apps\base\Webconfig as Webconfig;
use \clearos\apps\events\Event_Utils as Event_Utils;
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

        if ($this->framework->session->userdata('session_started') != '1')
            $this->start();
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

            if ($authorization->authenticate($username, $password)) {
                $is_valid = TRUE;
                // Set some acl values for main nav
                $nav_access = array(
                    'dashboard' => $authorization->check_acl($username, '/app/dashboard'),
                    'marketplace' => $authorization->check_acl($username, '/app/marketplace'),
                    'support' => $authorization->check_acl($username, '/app/support'),
                    'events' => $authorization->check_acl($username, '/app/events')
                );
                $this->framework->session->set_userdata('nav_acl', $nav_access);
            }
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
        //-----------------------------------------------------------------------

        if ($logged_in) {
            redirect('base/session/access_denied');
        } else {
            // Send invalid REST request to simple access denied page
            if ($_SERVER['SERVER_PORT'] == 83)
                redirect('base/session/rest');

            // Send the user to the login page.
            if (!($_SERVER['PHP_SELF'] === '/app/base/session/login')) {
                $post_redirect = base64_encode($_SERVER['PHP_SELF']);
                $post_redirect = strtr($post_redirect, '+/=', '-@_'); // Avoid these characters
                redirect('base/session/login/' . $post_redirect);
            }
        }
    }

    /**
     * Checks authentication.
     *
     * @return boolean TRUE if authenticated
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
     * Checks state of install wizard.
     *
     * @return boolean TRUE if wizard mode is required
     */

    public function is_install_wizard_mode()
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        if ($this->framework->session->userdata('wizard'))
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

        if (!clearos_load_library('language/Locale')) {
            $this->framework->session->set_userdata('lang_code', 'en_US');
            $this->framework->session->set_userdata('translation_code', 'en');
            $this->framework->session->set_userdata('text_direction', 'LTR');
            $this->framework->session->set_userdata('encoding', 'UTF-8');
        } else {
            try {
                $locale = new Locale();
                $translation_code = $locale->get_translation_code($code);
                $text_direction = $locale->get_text_direction($code);
                $encoding = $locale->get_encoding($code);

                $this->framework->session->set_userdata('lang_code', $code);
                $this->framework->session->set_userdata('translation_code', $translation_code);
                $this->framework->session->set_userdata('text_direction', $text_direction);
                $this->framework->session->set_userdata('encoding', $encoding);

                setcookie('clearos_lang', $code, time()+60*60*24*365, '/');
            } catch (Exception $e) {
                // Keep going
            }
        }

        // Clear the cache when changing the session language
        try {
            $this->framework->load->library('page');
            $this->framework->page->clear_cache();
        } catch (Exception $e) {
            // Keep going
        }
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

        // Language
        //---------

        // The language code can left alone if it is already set.

        if (! $this->framework->session->userdata('lang_code')) {
            $lang_code = 'en_US';

            // Logic:
            // - Default to system language on console (since that's what tconsole will be using)
            // - Use the save langugage in the browser cookie if it exists
            // - Default to system language, but override on web browser auto-detect
            // - Fall back to en_US
            if (clearos_console() && clearos_load_library('language/Locale')) {
                try {
                    $locale = new Locale();
                    $lang_code = $locale->get_language_code(TRUE);
                } catch (Exception $e) {
                    // Use default
                }
            } else if ($this->framework->input->cookie('clearos_lang')) {
                $lang_code = $this->framework->input->cookie('clearos_lang');
            } else if (clearos_load_library('language/Locale')) {
                $this->framework->load->library('user_agent');

                try {
                    $locale = new Locale();
                    $languages = $locale->get_languages();
                    $lang_code = $locale->get_language_code(TRUE);

                    foreach ($this->framework->agent->languages() as $browser_lang) {
                        $matches = array();

                        if (preg_match('/(.*)-(.*)/', $browser_lang, $matches))
                            $browser_lang = $matches[1] . '_' . strtoupper($matches[2]);
                        else
                            $browser_lang = $browser_lang . '_' . strtoupper($browser_lang);

                        if (array_key_exists($browser_lang, $languages)) {
                            $lang_code = $browser_lang;
                            break;
                        }
                    }
                } catch (Exception $e) {
                    // Use default
                }
            }

            $this->set_language($lang_code);
            setlocale(LC_ALL, $session['lang_code']);
        }

        // OS Info
        //--------

        $session['os_name'] = 'ClearOS';
        $session['os_version'] = '2.6';
        $session['redirect'] = '';

        if (clearos_load_library('base/OS')) {
            try {
                $os = new OS();
                $session['os_name'] = $os->get_name();
                $session['os_version'] = $os->get_version();
                $session['os_base_version'] = $os->get_base_version();
            } catch (Exception $e) {
                // Use default
            }
        }

        // Theme
        //------

        $session['theme'] = 'default';
        $session['theme_mode'] = 'normal';
        // If you have controllers where you need to validate a post was done (eg. restart server controller)
        // match against this value
        $session['form_post_verify'] = rand(0, 9999);

        if (clearos_load_library('base/Webconfig')) {
            $webconfig = new Webconfig();

            try {
                $session['theme'] = $webconfig->get_theme();
                // $session['theme_mode'] = $webconfig->get_theme_mode();
                // Load custom settings
                Logger::profile_framework(__METHOD__, __LINE__, "Loading custom theme settings");
                $session['theme_' . $session['theme']] = $webconfig->get_theme_settings();
            } catch (Exception $e) {
                // Use default
            }
        }

        // Wizard
        //-------

        if (file_exists('/var/clearos/base/wizard') && !clearos_console())
            $session['wizard'] = TRUE;

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

    public function start_authenticated($username, $custom_expiration = NULL)
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        $this->framework->session->set_userdata('logged_in', 'TRUE');
        $this->framework->session->set_userdata('username', $username);

        $segments = explode('/', $_SERVER['PHP_SELF']);
        $app_base = clearos_app_base($segments[2]);

        // Do we need to add alerts for developer?
        if (clearos_load_library('events/Event_Utils')) {
            if (!preg_match('/^\/usr\/clearos/', $app_base))
                Event_Utils::add_event('This app is using development code.', 'WARN', 'DEVEL_MODE_APP', 'devel', TRUE);
            else
                Event_Utils::resolve_event('DEVEL_MODE_APP');
            if (!preg_match('/^\/usr\/clearos/', __FILE__))
                Event_Utils::add_event('Framework is in development mode.', 'WARN', 'DEVEL_MODE_FRAME', 'devel', TRUE);
            else
                Event_Utils::resolve_event('DEVEL_MODE_FRAME');
        }

        // Override default session time-out?
        if ($custom_expiration !== NULL) {
            $this->framework->session->set_userdata(
                'custom_expiration', $custom_expiration
            );
        }
    }

    /**
     * Stops a login session.
     *
     * @return void
     */

    public function stop_authenticated()
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        setcookie('clearos_lang', $this->framework->session->userdata('lang_code'), time()+60*60*24*365, '/');

        $preserve = array('translation_code', 'lang_code', 'theme', 'theme_model');

        foreach ($this->framework->session->userdata as $key => $field) {
            if (in_array($key, $preserve))
                continue;

            $this->framework->session->unset_userdata($key);
        }
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
        $app_file = $app . '/' . $app . '_lang.php';

        foreach ($loaded as $loaded_lang) {
            if ($loaded_lang !== $app_file)
                $new_loaded[] = $loaded_lang;
        }

        $framework->session->CI->lang->is_loaded = $new_loaded;

        $framework->lang->load($app);
    }
}

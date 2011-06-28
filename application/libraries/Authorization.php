<?php

/**
 * Webconfig authorization class.
 *
 * @category   Framework
 * @package    Application
 * @subpackage Libraries
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

// Framework
//----------

use \clearos\framework\Logger as Logger;

// Factories
//----------

use \clearos\apps\users\User_Factory as User_Factory;

// Classes
//--------

use \clearos\apps\base\Access_Control as Access_Control;
use \clearos\apps\base\Posix_User as Posix_User;
use \clearos\apps\accounts\Accounts_Unavailable_Exception as Accounts_Unavailable_Exception;

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Webconfig authorization class.
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
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/
 */

class MY_Authorization
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
     * Authorization constructor.
     */

    public function __construct()
    {
        Logger::profile_framework(__METHOD__, __LINE__, 'Authorization Class Initialized');

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

        // Check Posix first
        //------------------

        if (clearos_load_library('base/Posix_User')) {
            $user = new Posix_User($username);

            if ($user->check_password($password))
                $is_valid = TRUE;
        }

        // Then check via user factory
        //----------------------------

        if (! $is_valid) {
            if (clearos_load_library('users/User_Factory')) {
                try {
                    $user = User_Factory::create($username);

                    if ($user->check_password($password))
                        $is_valid = TRUE;
                } catch (Accounts_Unavailable_Exception $e) {
                    // Not fatal
                }
            }
        }

        // Set login session variables
        //----------------------------

        if ($is_valid) {
            $this->framework->session->set_userdata('logged_in', 'TRUE');
            $this->framework->session->set_userdata('username', $username);
        }

        return $is_valid;
    }

    /**
     * Checks the page authorization.
     *
     * @return void
     */

    public function check()
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        $username = $this->framework->session->userdata('username');
        $logged_in = (bool)$this->framework->session->userdata('logged_in');

        // Return right away if access granted
        //------------------------------------

        if ($this->check_acl($username, $_SERVER['PHP_SELF']))
            return;

        // If logged in but denied access, point the user in the right direction.
        // If not logged in, send the user to the login page.
        //-----------------------------------------------------------------------

        if ($logged_in) {
            redirect('base/session/access_denied');
        } else {
            if (!($_SERVER['PHP_SELF'] === '/app/base/session/login'))
                redirect('base/session/login');
        }
    }

    /**
     * Check access control for given user and URL.
     *
     * @param string $username username
     * @param string $url      URL
     *
     * @return true if access is permitted
     */

    public function check_acl($username, $url)
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        // root - allow everything
        //------------------------

        if ($username === 'root') {
            Logger::SysLog("webconfig", "access control - full access granted for $username on $url");
            return TRUE;
        }

        // Bail if access control is not installed
        //----------------------------------------

        if (! clearos_load_library('base/Access_Control')) {
            Logger::SysLog("webconfig", "access control - access denied on $url");
            return FALSE;
        }

        // Access control (if installed)
        //------------------------------

        try {
            $access = new Access_Control();

            $allow_authenticted = $access->get_authenticated_access_state();
            $allow_custom = $access->get_custom_access_state();
            $valid_urls = $access->get_valid_pages_details($username);
        } catch (Exception $e) {
            // Good security practice is to stop right away on error
            echo "Could not get authorization settings: ";
            echo $e->GetMessage();
            exit();
        }

        $valid_authenticated_urls = $valid_urls[Access_Control::TYPE_AUTHENTICATED];
        $valid_custom_urls = $valid_urls[Access_Control::TYPE_CUSTOM];
        $valid_public_urls = $valid_urls[Access_Control::TYPE_PUBLIC];

        // custom access - allow access to configured URLs
        //------------------------------------------------

        if ($allow_custom && $username) {
            foreach ($valid_custom_urls as $valid_url) {
                $valid_url_regex = preg_quote($valid_url, '/');

                if (preg_match("/$valid_url_regex/", $url)) {
                    Logger::SysLog("webconfig", "access control - custom access granted for $username on $url (matched $valid_url)");
                    return TRUE;
                }
            }
        }

        // normal user - allow access to user-specific URLs
        //------------------------------------------------------------

        if ($allow_authenticted && $username) {
            foreach ($valid_authenticated_urls as $valid_url) {
                $valid_url_regex = preg_quote($valid_url, '/');

                if (preg_match("/$valid_url_regex/", $url)) {
                    Logger::SysLog("webconfig", "access control - user access granted for $username on $url (matched $valid_url)");
                    return TRUE;
                }
            }
        }

        // public pages
        //-------------

        foreach ($valid_public_urls as $valid_url) {
            $valid_url_regex = preg_quote($valid_url, '/');

            if (preg_match("/$valid_url_regex/", $url)) {
                Logger::SysLog("webconfig", "access control - public access granted on $url (matched $valid_url)");
                return TRUE;
            }
        }

        // Otherwise, ACL denied
        //----------------------

        $user_log = ($username) ? " for $username" : '';
        Logger::SysLog("webconfig", "access control - access denied$user_log on $url");

        return FALSE;
    }

    /**
     * Checks authentication.
     */

    function is_authenticated()
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        if ($this->framework->session->userdata('logged_in'))
            return TRUE;
        else
            return FALSE;
    }

    /**
     * Login.
     *
     */

    function login()
    {
        Logger::profile_framework(__METHOD__, __LINE__);
    }

    /**
     * Logout.
     *
     */

    function logout()
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        $this->framework->session->unset_userdata('logged_in');
        $this->framework->session->unset_userdata('username');
        $this->framework->session->unset_userdata('session_started');
    }
    
    /**
     * Sets session variables when authenticated.
     *
     * @return void
     */

    function set_session_authenticated()
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        $webconfig = new Webconfig();

        // Organization
        //-------------

        $orgname = "";

        if (file_exists(COMMON_CORE_DIR . "/api/Organization.php")) {
            include_once COMMON_CORE_DIR . "/api/Organization.php" ;

            try {
                $organization = new Organization();
                $orgname = $organization->GetName();
            } catch (Exception $e) {
                // Use default
            }
        }

        // Full name
        //----------

        $fullname = "";

        if (file_exists(COMMON_CORE_DIR . "/api/User.php")) {
            include_once COMMON_CORE_DIR . "/api/User.php" ;

            try {
                if ($_SESSION['user_login'] == "root") {
                    $fullname = LOCALE_LANG_ADMINISTRATOR;
                } else {
                    $user = new User($_SESSION['user_login']);
                    $userinfo = $user->GetInfo();
                    // TODO: not all cultures use "firstname lastname"
                    $fullname = $userinfo['firstName'] . " " . $userinfo['lastName'];
                }
            } catch (Exception $e) {
                // Use default
            }
        }

        $_SESSION['system_fullname'] = $fullname;
        $_SESSION['system_organization'] = $orgname;
    }
}

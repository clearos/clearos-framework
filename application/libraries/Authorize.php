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

$bootstrap = isset($_ENV['CLEAROS_BOOTSTRAP']) ? $_ENV['CLEAROS_BOOTSTRAP'] : '/usr/clearos/framework/shared';
require_once $bootstrap . '/bootstrap.php';

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

use \clearos\framework\Logger as Logger;

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

class MY_Authorize
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
     * Authorize constructor.
     */

    public function __construct()
    {
        Logger::profile_framework(__METHOD__, __LINE__, 'Authorize Class Initialized');

        $this->framework =& get_instance();
    }

    /**
     * Checks the page authorization.
     *
     * @return void
     */

    public function check()
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        // FIXME - the redirects create a dependency on app-base.  Fix this (by moving into application directory?)

        $username = $this->framework->session->userdata('username');
        $logged_in = (bool)$this->framework->session->userdata('logged_in');

        // Return right away if access granted
        //------------------------------------

        // FIXME: disabled authentication 
        return;

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

        clearos_load_library('base/Webconfig');

        try {
            $webconfig = new Webconfig();

            $valid_urls = $webconfig->GetValidPages($username);
            $allow_users = $webconfig->GetUserAccessState();
            $allow_subadmins = $webconfig->GetAdminAccessState();
        } catch (Exception $e) {
            // Good security practice is to stop right away on error
            echo "Could not get authorization settings: ";
            echo $e->GetMessage();
            exit();
        }

        $valid_public_urls = $valid_urls[Webconfig::ACCESS_TYPE_PUBLIC];
        $valid_subadmin_urls = $valid_urls[Webconfig::ACCESS_TYPE_SUBADMIN];
        $valid_user_urls = $valid_urls[Webconfig::ACCESS_TYPE_USER];

        // root - allow everything
        //------------------------

        // FIXME: move this above the potential exception above?

        if ($username === 'root') {
            Logger::SysLog("webconfig", "access control - full access granted for $username on $url");
            return TRUE;
        }

        // TODO: local administrators group? or add flag for * in sub-administrators?
        /*
        } else if ($username === 'root') {
            $log_message = "local admin access granted for $username on $url";
            $is_valid = TRUE;
        */

        // local sub-administrator - allow access to configured URLs
        //----------------------------------------------------------

        if ($allow_subadmins && $username) {
            foreach ($valid_subadmin_urls as $valid_url) {
                $valid_url_regex = preg_quote($valid_url, '/');

                if (preg_match("/$valid_url_regex/", $url)) {
                    Logger::SysLog("webconfig", "access control - local subadmin access granted for $username on $url (matched $valid_url)");
                    return TRUE;
                }
            }
        }

        // normal user - allow access to user-specific URLs
        //------------------------------------------------------------

        if ($allow_users && $username) {
            foreach ($valid_user_urls as $valid_url) {
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

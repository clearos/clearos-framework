<?php

///////////////////////////////////////////////////////////////////////////////
//
// Copyright 2010 ClearFoundation
//
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

/**
 * Webconfig authorization class.
 *
 * @package Framework
 * @author {@link http://www.clearfoundation.com/ ClearFoundation}
 * @license http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @copyright Copyright 2003-2010 ClearFoundation
 */

///////////////////////////////////////////////////////////////////////////////
// B O O T S T R A P
///////////////////////////////////////////////////////////////////////////////

$bootstrap = isset($_ENV['CLEAROS_BOOTSTRAP']) ? $_ENV['CLEAROS_BOOTSTRAP'] : '/usr/clearos/framework/shared';
require_once($bootstrap . '/bootstrap.php');

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

clearos_load_library('base/Engine');

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Authentication for web pages.
 *
 * The security model is simple - all protected pages start with a call to
 * WebAuthenticate().  The function does one of three things:
 *
 *  - 1) Returns (quietly) on success
 *  - 2) Returns a "login failed" username/password web form
 *  - 3) Returns an "access denied" page if user is accessing an unauthorized page
 *
 * @return  void
 */

class MY_Authorize extends Engine
{
	///////////////////////////////////////////////////////////////////////////////
	// M E T H O D S
	///////////////////////////////////////////////////////////////////////////////

	/**
	 * Authorize constructor.
	 */

	public function __construct()
	{
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__, 'Authorize Class Initialized');

		parent::__construct();
	}

	function WebAuthenticate()
	{
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__);

		// Forward to wizard when required
		//--------------------------------

		if (file_exists(Webconfig::FILE_SETUP_FLAG) && 
			!preg_match("/\/app\/setup\..*/", $_SERVER['PHP_SELF']) &&
			!(BCONFIG_CONSOLE)
			) {
			// TODO: not very clean... the wizard needs to pull in Ajax helper pages
			if (!(preg_match("/\.js\./", $_SERVER['PHP_SELF']) || preg_match("/\.xml\./", $_SERVER['PHP_SELF']))) {
				WebForwardPage("/app/setup.php");
				exit;
			}
		}

		// Logout requested
		//-----------------

		if (isset($_REQUEST['reserved_logout']))  {
			// We need the session data for formatting, so don't destroy the
			// session before grabbing our "you have been logged out" HTML.

			ob_start();
			WebHeader($_SESSION['system_osname'], false);
			WebAuthenticateDisplayLogin("", "");
			WebFooter(false);
			$html = ob_get_contents();
			ob_end_clean();

			session_destroy();
			unset($_SESSION);

			echo $html;

			exit;

		// Already logged in as root - return ok right away
		//-------------------------------------------------

		} else if (isset($_SESSION['system_login'])) {
			return;

		// Already logged in as user/subadmin - check if this page is allowed
		//-------------------------------------------------------------------

		} else if (isset($_SESSION['user_login']))  {
			WebAuthenticateCheckAcl($_SESSION['user_login'], $_SERVER['PHP_SELF']);

		// Kill X if requested
		//--------------------
		} else if (isset($_REQUEST['ExitConsole']))  {
			require_once(COMMON_CORE_DIR . '/api/ShellExec.php');
			$shell = new ShellExec();
			$shell->Execute(Webconfig::CMD_KILLALL, 'X', true);
			exit;

		
		// Setup wizard required
		//----------------------

		// Not logged in
		//--------------

		} else {
			$username = isset($_POST['reserved_username']) ? $_POST['reserved_username'] : "";
			$password = isset($_POST['reserved_password']) ? $_POST['reserved_password'] : "";

			// No credentials sent, so show a helpful login screen
			//----------------------------------------------------

			if (!($username && $password)) {
				WebHeader($_SESSION['system_osname'], false);
				WebAuthenticateDisplayLogin("", "");
				WebFooter(false);
				exit;

			// Credentials sent, so try to authenticate
			//------------------------------------------

			} else {
				sleep(2); // a small delay for brute force attacks

				// For the root user, check /etc/passwd
				// For other users, check LDAP

				$passwordok = false;
				$allowadmins = false;
				$allowusers = true;

				if ($username == "root") {
					if (! file_exists(COMMON_CORE_DIR . '/api/PosixUser.php'))
						exit();

					require_once(COMMON_CORE_DIR . '/api/PosixUser.php');

					try {
						$user = new PosixUser($username);
						$passwordok = $user->CheckPassword($password);
					} catch (Exception $e) {
						WebHeader("", false);
						infobox_warning($e->GetMessage());
						Webfooter();
						exit();
					}
				} else {
					if (! file_exists(COMMON_CORE_DIR . '/api/User.php'))
						exit();

						require_once(COMMON_CORE_DIR . '/api/User.php');

						try {
							$user = new User($username);
							$passwordok = $user->CheckPassword($password, 'pcnWebconfigPassword');
					} catch (Exception $e) {
						WebHeader("", false);
						infobox_warning($e->GetMessage());
						Webfooter();
						exit();
					}
				}

				if (($username == "root") && $passwordok) {
					ClearOsLoggerSysLog("webconfig", "login - root login successful");
					$_SESSION['system_login'] = "root";
					$_SESSION['user_login'] = "root";
					WebSetSessionAuthenticated();

				} else if ($allowadmins && $passwordok && in_array($username, $validadmins)) {
					ClearOsLoggerSysLog("webconfig", "login - $username sub-admin login successful");
					$_SESSION['user_login'] = $username;
					WebSetSessionAuthenticated();
					WebAuthenticateCheckAcl($username, $_SERVER['PHP_SELF']);

				} else if ($allowusers && $passwordok) {
					ClearOsLoggerSysLog("webconfig", "login - $username user login successful");
					$_SESSION['user_login'] = $username;
					WebSetSessionAuthenticated();
					WebAuthenticateCheckAcl($username, $_SERVER['PHP_SELF']);

				} else {
					ClearOsLoggerSysLog("webconfig", "login - $username login failed");

					WebHeader($_SESSION['system_osname'], false);
					WebAuthenticateDisplayLogin($username, $password, BCONFIG_LANG_ERRMSG_LOGIN_FAILED);
					WebFooter(false);
					exit;
				}
			}
		}
	}

	/**
	 * Displays a login web page form.
	 */

	function WebAuthenticateDisplayLogin($username, $password, $warning = null)
	{
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__);

		if (BCONFIG_CONSOLE)
			$login = "root <input type='hidden' name='reserved_username' value='root' />";
		else
			$login = "<input type='text' name='reserved_username' value='$username' />";

		WebHeaderLayout("splash");


		if (file_exists(ClearOsConfig::$htdocs_path . "/themes/" . $_SESSION['system_template'] . "/widgets/login.php")) {
			require(ClearOsConfig::$htdocs_path . "/themes/" . $_SESSION['system_template'] . "/widgets/login.php");
		} else {
			if (! empty($warning))
				infobox_warning($warning);

			WebFormOpen();
			WebTableOpen(BCONFIG_LANG_LOGIN, "450");
			echo "
				<tr>
					<td width='150' nowrap class='mytablesubheader'>" . LOCALE_LANG_USERNAME . "</td>
					<td>$login</td>
				</tr>
				<tr>
					<td nowrap class='mytablesubheader'>" . LOCALE_LANG_PASSWORD . "</td>
					<td><input type='password' name='reserved_password' value='$password' /></td>
				</tr>
				<tr>
					<td class='mytablesubheader'>&nbsp; </td>
					<td>" . WebButtonContinue("Login") . "</td>
				</tr>
			";
			WebTableClose("450");
			WebFormClose();
		}

		WebFooter("splash");
	}

	/**
	 * Checks to see if given username is allowed to view given page.
	 * If page is not allowed, a redirect to the first valid page is attempted.
	 */

	function WebAuthenticateCheckAcl($username, $page)
	{
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__);

		$webconfig = new Webconfig();

		// Allow helper pages (for example, data.xml.php and date.js.php)
		$authpage = preg_replace("/\.(inc|js|xml)\.php$/", ".php", $page);

		try {
			if (isset($_SESSION['system_valid_pages_regular'])) {
				$validregular = explode("|", $_SESSION['system_valid_pages_regular']);
				$validadmin = explode("|", $_SESSION['system_valid_pages_admin']);
				$allowusers = (bool) $_SESSION['system_allow_users'];
				$allowadmins = (bool) $_SESSION['system_allow_admins'];
			} else {
				$allowusers = $webconfig->GetUserAccessState();
				$allowadmins = $webconfig->GetAdminAccessState();
				$validpages = $webconfig->GetValidPages($username);
				$validregular = $validpages[Webconfig::TYPE_USER_REGULAR];
				$validadmin = $validpages[Webconfig::TYPE_USER_ADMIN];
				
				$_SESSION['system_valid_pages_regular'] = implode("|", $validregular);
				$_SESSION['system_valid_pages_admin'] = implode("|", $validadmin);
				$_SESSION['system_allow_users'] = $webconfig->GetUserAccessState();
				$_SESSION['system_allow_admins'] = $webconfig->GetAdminAccessState();
			}
		} catch (Exception $e) {
			WebHeader("", false);
			infobox_warning($e->GetMessage());
			WebFooter();
			exit();
		}

		if ($allowadmins && in_array($authpage, $validadmin)) {
			ClearOsLoggerSysLog("webconfig", "access control - $username user accessed $page");
			$isvalid = true;
		} else if ($allowusers && in_array($authpage, $validregular)) {
			ClearOsLoggerSysLog("webconfig", "access control - $username user accessed $page");
			$isvalid = true;
		} else if (preg_match("/^\/index.php$/", $page) && isset($validregular[0])) {
			// Forward user logins on document root to first valid page
			WebForwardPage($validregular[0]);
			exit;
		} else {
			ClearOsLoggerSysLog("webconfig", "access control - $username denied access to $page");
			$isvalid = false;
		}

		if (! $isvalid) {
			if (isset($validregular[0])) {
				WebHeader("", false);
				infobox_warning(
					LOCALE_LANG_ACCESS_DENIED . "<br><br>" .
					"<a href=https://" . $_SERVER["HTTP_HOST"] . $validregular[0] . ">" .
					BCONFIG_LANG_USE_THIS_PAGE_INSTEAD . "</a>"
				);
				WebFooter(false);
				exit();
			} else {
				WebHeader("", false);
				infobox_warning(LOCALE_LANG_ACCESS_DENIED);
				WebFooter(false);
				exit();
			}
		}
	}

	/**
	 * Sets session variables when authenticated.
	 */

	function WebSetSessionAuthenticated()
	{
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__);

		$webconfig = new Webconfig();

		// Organization
		//-------------

		$orgname = "";

		if (file_exists(COMMON_CORE_DIR . "/api/Organization.php")) {
			require_once(COMMON_CORE_DIR . "/api/Organization.php");

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
			require_once(COMMON_CORE_DIR . "/api/User.php");

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

// vim: syntax=php ts=4
?>

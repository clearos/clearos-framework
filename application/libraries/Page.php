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
 * Webconfig page class.
 *
 * This class provides the mechanism for managing the layout of a webconfig
 * page.  The view of a given ClearOS App is managed by the app developer,
 * while the view of the following is managed by this class:
 *
 * - Header 
 * - Footer
 * - Menu
 * - Summary
 * - Status messages
 *
 * This class handles the data management (e.g. parsing menu meta data), 
 * while the theme developer handles the look and feel.  This class also 
 * provides the following variables for use in their overall webconfig page:
 *
 * - login (if logged in)
 * - full name (if logged in)
 * - hostname
 * - registration status
 * - locale
 * - OS name
 * - OS version
 * - theme
 * - theme mode
 * - app 16x16 icon
 * - app 32x32 icon
 * - success message (e.g. firewall rule has been deleted)
 * - warning message (e.g. OpenVPN is running, but the firewall is not allowing connections)
 * - page layout (default, splash, wizard(?))
 * - page title
 *
 * This class also handles exceptions.  When an exception occurs in a
 * controller, further processing is halted.  The exception is then shown
 * via the handle_exception() method.
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
 * Webconfig system status class.
 *
 * @return  void
 */

class MY_Page extends Engine
{
	///////////////////////////////////////////////////////////////////////////////
	// C O N S T A N T S
	///////////////////////////////////////////////////////////////////////////////
	// FIXME: constants are not accessible via CodeIgniter calls?  Investigate.

	public $constant_splash = 'splash';
	public $constant_default = 'default';

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
	 * Page constructor.
	 */

	public function __construct()
	{
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__, 'Page Class Initialized');

		$this->framework =& get_instance();

		parent::__construct();
	}

	/**
	 * Handles a fatal/uncaught exception in a controller.
	 */

	public function exception($message, $view = 'page')
	{
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__);

		$data['message'] = $message;
		$page['title'] = 'Exception'; // FIXME; localize... or maybe not?

		if ($view == 'form') {
			$this->framework->load->view('theme/exception', $data);
		} else {
			$this->framework->load->view('theme/header', $page);
			$this->framework->load->view('theme/exception', $data);
			$this->framework->load->view('theme/footer', $page);
		}
	}

	/**
	 * Loads the required theme files. 
	 *
	 * The theme hooks are loaded after the controller has been initialized.
	 * - doctype.php
	 * - head.php
	 * - header.php
	 * - footer.php
	 * - theme.php  // FIXME:
	 *
	 * This is called by a CodeIgniter hook instead of the constructor since
	 * the user session has not been initialized in the constructor.
	 */

	public function load_theme()
	{
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__);

		$theme_files = array('doctype.php', 'head.php', 'header.php', 'footer.php', 'theme.php');
		$path = ClearOsConfig::GetThemePath($this->framework->session->userdata('theme'));

// FIXME: "widgets" path should be changed to something else?
// FIXME: "theme.php" should be changed to widgets.php?

		foreach ($theme_files as $file) {
			ClearOsLogger::ProfileFramework(__METHOD__, __LINE__, "Loading theme file $file");
			$full_path = $path . '/widgets/' . $file;

			if (file_exists($full_path))
				require($full_path);
			else
				echo "<p class='alert'>Theme file is missing: $file</p>";
		}
	}

	/**
	 * Handles a page success message.
	 */

	public function success($message)
	{ 
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__);

		$this->framework->session->set_userdata('status_success', $message);
	}

	/**
	 * Displays the footer view.
	 *
	 * @param array $view_data view data
	 * @return void
	 */

	public function view_footer($view_data)
	{
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__);

		$page_data = $this->_load_page_data($view_data);

		echo page_footer($page_data);
	}

	/**
	 * Displays the header view.
	 *
	 * @param array $view_data view data
	 * @return void
	 */

	public function view_header($view_data)
	{
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__);

		$page_data = $this->_load_page_data($view_data);

		echo page_doctype() . "\n";
		echo $this->_build_page_head($page_data);
		echo page_header($page_data);
	}

	/**
	 * Displays the exception view.
	 *
	 * @param string $message error message
	 * @return void
	 */

	public function view_exception($message)
	{
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__);

		echo infobox_critical($message);
	}

	///////////////////////////////////////////////////////////////////////////////
	// P R I V A T E  M E T H O D S
	///////////////////////////////////////////////////////////////////////////////

	/**
	 * Returns the HTML head section.
	 *
	 * @param array $page_data page data
	 * @return string HTML head section
	 */

	protected function _build_page_head($page_data)
	{
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__);

		// Adding hostname to the title is handy when managing multiple systems
		//---------------------------------------------------------------------

		$title = $page_data['title'];

		if ($this->framework->session->userdata('hostname'))
			$title = $this->framework->session->userdata('hostname') . " - " . $title;

		// Determine the version to use (trunk, 6.0, etc)
		//-----------------------------------------------

		$segments = explode('/', $_SERVER['PHP_SELF']);
		$app = $segments[2];

		if (isset(ClearOsConfig::$clearos_devel_versions['app'][$app]))
			$app_version = ClearOsConfig::$clearos_devel_versions['app'][$app] . '/';
		else if (isset(ClearOsConfig::$clearos_devel_versions['app']['default']))
			$app_version = ClearOsConfig::$clearos_devel_versions['app']['default'] . '/';
		else
			$app_version = "";

		// Add page-specific head links.  For example, 
		// To support different versions running in parallel, determine the app
		// version e.g. /app/dhcp/htdocs, /app/dhcp/tags/5.1/htdocs, etc.
		//-------------------------------------------------------------------------

		$js = '/' . $app . '/' . $app_version . 'htdocs/' . $app . '.js.php';
		$css = '/' . $app . '/' . $app_version . 'htdocs/' . $app . '.css';
		// FIXME: should not be using app_version below
		$theme_path = '/themes/' . $this->framework->session->userdata('theme') . '/' . $app_version;
		$theme_basepath = ClearOsConfig::$themes_path . '/' . $this->framework->session->userdata('theme') . '/' . $app_version;

		$page_auto_head = '';

		if (file_exists(ClearOsConfig::$apps_path . '/' . $js))
			$page_auto_head .= "<script type='text/javascript' src='/approot" . $js . "'></script>\n";

		if (file_exists(ClearOsConfig::$apps_path . '/' . $css))
			$page_auto_head .= "<link type='text/css' href='/approot" . $css ."' rel='stylesheet'>";

		// <html>
		//-------------------
		
		$head = "<html dir='" . $this->framework->session->userdata('textdir') . "'>\n\n";

		// <head>: page_head is defined in the head.php theme file
		//--------------------------------------------------------

		$head .= "<!-- Head Start -->
<head>

<!-- Basic Head Information -->
<title>$title</title>
<meta http-equiv='Content-Type' content='text/html; charset=" . $this->framework->session->userdata('charset') . "'>

<!-- Jquery -->
<script type='text/javascript' src='/js/jquery-1.4.4.min.js'></script>
";

		$head .= page_head($theme_path);

		$head .= "<!-- Page-specific Head -->
$page_auto_head
</head>
<!-- Head end -->

";

		return $head;
	}

	/**
	 * Returns menu data in an array
	 *
	 * @return array menu meta data
	 */

	protected function _load_menu_data()
	{
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__);

		$menu_data['menus'] = array(
			'/app/network' => array(
				'section' => 'Network',
				'subsection' => 'Settings',
				'title' => 'IP Settings',
				'type' => 'configuration',
				'priority' => '2001'
			),

			'/app/dhcp' => array(
				'section' => 'Network',
				'subsection' => 'Settings',
				'title' => 'DHCP Server',
				'type' => 'configuration',
				'priority' => '2001'
			),

			'/app/firewall/incoming' => array(
				'section' => 'Network',
				'subsection' => 'Firewall',
				'title' => 'Incoming',
				'type' => 'configuration',
				'priority' => '2001'
			),

			'/app/devel' => array(
				'section' => 'System',
				'subsection' => 'Developer',
				'title' => 'Theme',
				'type' => 'configuration',
				'priority' => '2001'
			),

			'/app/date' => array(
				'section' => 'System',
				'subsection' => 'Settings',
				'title' => 'Date',
				'type' => 'configuration',
				'priority' => '2001'
			),

			'/app/filescan' => array(
				'section' => 'Server',
				'subsection' => 'File and Print ',
				'title' => 'File Scanner',
				'type' => 'configuration',
				'priority' => '2001'
			),

			'/app/dashboard' => array(
				'section' => 'Reports',
				'subsection' => 'Overview',
				'title' => 'Dashboard',
				'type' => 'configuration',
				'priority' => '2001'
			)
		);

		return $menu_data;
	}

	/**
	 * Returns page meta data in an array
	 *
	 * @param array $view_data view data
	 * @return array page meta data
	 */

	protected function _load_page_data($view_data)
	{
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__);

		$menu_data = $this->_load_menu_data();
		$session_data = $this->_load_session_data();

		$page_data = array_merge($view_data, $session_data, $menu_data);

		return $page_data;
	}

	/**
	 * Returns session data in an array.
	 *
	 * @return array menu meta data
	 */

	protected function _load_session_data()
	{
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__);

		$session_data = array();

// FIXME
$session_data['theme_url'] = '/themes/clearos6x/trunk';

		// The "status_success" message is passed via the session
		//-------------------------------------------------------

		if ($this->framework->session->userdata('status_success')) {
			$session_data['status_success'] = $this->framework->session->userdata('status_success');
			$this->framework->session->unset_userdata('status_success');
		}

		return $session_data;
	}
}

// vim: syntax=php ts=4
?>

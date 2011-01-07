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

	const COMMAND_FIND = '/usr/bin/find';
	const TYPE_DEFAULT = 'default';
	const TYPE_SPLASH = 'splash';
	const TYPE_WIZARD = 'wizard';

	///////////////////////////////////////////////////////////////////////////////
	// V A R I A B L E S
	///////////////////////////////////////////////////////////////////////////////

	/**
	 * @var object framework instance
	 */

	protected $framework = NULL;

	/**
	 * @var array page information
	 */

	public $data = array();

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
	}

	/**
	 * Loads the required theme files. 
	 *
	 * The theme hooks are loaded after the controller has been initialized.
	 * - doctype.php
	 * - head.php
	 * - header.php
	 * - footer.php
	 * - widgets.php
	 *
	 * This is called by a CodeIgniter hook instead of the constructor since
	 * the user session has not been initialized in the constructor.
	 */

	public function load_theme()
	{
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__);

		$theme_files = array('doctype.php', 'head.php', 'header.php', 'footer.php', 'widgets.php');
		$path = ClearOsConfig::GetThemePath($this->framework->session->userdata('theme'));

		foreach ($theme_files as $file) {
			ClearOsLogger::ProfileFramework(__METHOD__, __LINE__, "Loading theme file $file");
			$full_path = $path . '/core/' . $file;

			if (file_exists($full_path))
				require($full_path);
			else
				echo "<p class='alert'>Theme file is missing: $file</p>";
		}
	}

	/**
	 * Sets the layout for the page.
	 */

	public function set_layout($layout)
	{
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__);

		$this->data['layout'] = $layout;
	}

	/**
	 * Sets the title for the page.
	 */

	public function set_title($title)
	{
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__);

		$this->data['title'] = $title;
	}

	/**
	 * Handles a page success message.
	 */

	public function set_success($message)
	{ 
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__);

		$this->framework->session->set_userdata('status_success', $message);
	}

	/**
	 * Displays the footer view.
	 *
	 * @return void
	 */

	public function view_footer()
	{
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__);

	// FIXME: not necessary since it is done on view_header.  Clean up.
	//	$this->_load_meta_data();

		echo page_footer($this->data);
	}

	/**
	 * Displays the header view.
	 *
	 * @return void
	 */

	public function view_header()
	{
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__);

		$this->_load_meta_data();

		echo page_doctype() . "\n";
		echo $this->_build_page_head($this->data);
		echo page_header($this->data);
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

		$this->data['title'] = 'Exception';

		$this->view_header();
		echo infobox_critical($message);
		$this->view_footer();
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
		$sub_app = $segments[3];

		$app_path = ClearOsConfig::GetAppUrl($app);
		$theme_path = ClearOsConfig::GetThemeUrl($this->framework->session->userdata('theme'));

		// Add page-specific head links
		//-----------------------------

		$css = $app_path . '/' . $app . '.css';
		$js = $app_path . '/' . $app . '.js.php';
		$js_sub_app = $app_path . '/' . $sub_app . '.js.php';

		$page_auto_head = '';

		if (file_exists(ClearOsConfig::$apps_path . '/' . $js))
			$page_auto_head .= "<script type='text/javascript' src='/approot" . $js . "'></script>\n";

		if (file_exists(ClearOsConfig::$apps_path . '/' . $js_sub_app))
			$page_auto_head .= "<script type='text/javascript' src='/approot" . $js_sub_app . "'></script>\n";

		if (file_exists(ClearOsConfig::$apps_path . '/' . $css))
			$page_auto_head .= "<link type='text/css' href='/approot" . $css ."' rel='stylesheet'>\n";

		// <html>
		//-------------------
		
		$head = "<html dir='" . $this->framework->session->userdata('textdir') . "'>\n\n";

		// <head> commom
		//-------------------

		$head .= "<!-- Head -->
<head>

<!-- Basic Head Information -->
<title>$title</title>
<meta http-equiv='Content-Type' content='text/html; charset=" . $this->framework->session->userdata('charset') . "'>

<!-- Jquery -->
<script type='text/javascript' src='/js/jquery-1.4.4.min.js'></script>
";

		// <head> extras defined in theme (head.php)
		//------------------------------------------

		$head .= page_head($theme_path);

		// <head> extras defined in app
		//------------------------------------------

		if ($page_auto_head)
			$head .= "<!-- Page-specific Head -->\n$page_auto_head\n";

		// </head> all done
		//------------------------------------------

		$head .= "</head>\n\n";

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

		// Load menu files in app directory
		//---------------------------------

		exec(MY_Page::COMMAND_FIND . ' ' . ClearOsConfig::$apps_path . " -name menu.php", $menu_list, $retval);

		if ($retval !== 0) {
			// FIXME: die? 
		}

		$menu = array();

		foreach ($menu_list as $menu_file)
			include_once($menu_file);

		// Load menu order preferences
		//----------------------------

		// $category_order = array();

		// Set ordering
		//-------------

		$sorted = array();

		foreach ($menu as $url => $detail) {
			$sorted[$detail['category'] . $detail['subcategory'] . $detail['title']] = $url;
		}

		ksort($sorted);

		$menu_data = array();

		foreach ($sorted as $sort => $url) {
			$menu_data[$url] = $menu[$url];
		}

/*
echo "menu<pre>";
	print_r($menu_data);
	echo "</pre>";
*/

		return $menu_data;

		$menu_data['menus'] = array(
			'/app/date' => array(
				'section' => 'Developer',
				'subsection' => 'Examples',
				'title' => 'Date (Simple Form)',
				'type' => 'configuration',
				'priority' => '2001'
			),

			'/app/dns' => array(
				'section' => 'Developer',
				'subsection' => 'Examples',
				'title' => 'Local DNS (CRUD)',
				'type' => 'configuration',
				'priority' => '2001'
			),

			'/app/dhcp' => array(
				'section' => 'Developer',
				'subsection' => 'Examples',
				'title' => 'DHCP Server (Advanced)',
				'type' => 'configuration',
				'priority' => '2001'
			),
		);
/*
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

			'/app/dns' => array(
				'section' => 'Network',
				'subsection' => 'Settings',
				'title' => 'Local DNS Server',
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

		return $menu;
*/
	}

	/**
	 * Loads the page meta data into the data class variable.
	 *
	 * @return void
	 */

	protected function _load_meta_data()
	{
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__);

		$view_data = $this->_load_view_data();
		$menu_data['menus'] = $this->_load_menu_data();
		$session_data = $this->_load_session_data();

		$this->data = array_merge($this->data, $view_data, $session_data, $menu_data);
	}


	/**
	 * Returns page session data in an array.
	 *
	 * @return array session meta data
	 */

	protected function _load_session_data()
	{
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__);

		$session_data = array();

		// Grab all the session values
		//----------------------------

		foreach ($this->framework->session->userdata as $key => $value)
			$session_data[$key] = $value;

		// The "status_success" message is a flash value... delete it
		//-----------------------------------------------------------

		if ($this->framework->session->userdata('status_success'))
			$this->framework->session->unset_userdata('status_success');

		return $session_data;
	}

	/**
	 * Returns view page data in an array.
	 *
	 * @return array view meta data
	 */

	protected function _load_view_data()
	{
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__);

		$view_data = array();

		// Set layout to 'default'
		if (empty($this->data['layout']))
			$view_data['layout'] = MY_Page::TYPE_DEFAULT;

		return $view_data;
	}
}

// vim: syntax=php ts=4
?>

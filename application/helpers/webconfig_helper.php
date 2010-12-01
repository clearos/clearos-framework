<?php

///////////////////////////////////////////////////////////////////////////////
//
// Copyright 2002-2010 ClearFoundation
//
//////////////////////////////////////////////////////////////////////////////
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
 * Webconfig.
 *
 * Webconfig is used to give the web-interface a consistent look and feel.
 *
 * @package Framework
 * @author {@link http://www.clearfoundation.com/ ClearFoundation}
 * @license http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @copyright Copyright 2010 ClearFoundation
 */

///////////////////////////////////////////////////////////////////////////////
// B O O T S T R A P
///////////////////////////////////////////////////////////////////////////////

$bootstrap = isset($_ENV['CLEAROS_BOOTSTRAP']) ? $_ENV['CLEAROS_BOOTSTRAP'] : '/usr/clearos/framework/shared';
require_once($bootstrap . '/bootstrap.php');

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

clearos_load_language('base');

// FIXME
define('CLEAROS_MOBILE', 'mobile');

//////////////////////////////////////////////////////////////////////////////
// H E A D E R  /  F O O T E R
///////////////////////////////////////////////////////////////////////////////

/**
 * Returns the HTML head section.
 *
 * @param string $title page title
 * @return string HTML head section
 */

function clearos_html_head($title)
{
	ClearOsLogger::Profile(__METHOD__, __LINE__);

	$framework =& get_instance();

	// Adding hostname to the title is handy when managing multiple systems
	//---------------------------------------------------------------------

	if ($framework->session->userdata('hostname'))
		$title = $framework->session->userdata('hostname') . " - " . $title;

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
	$theme_path = '/themes/' . $framework->session->userdata('theme') . '/' . $app_version;
	$theme_basepath = ClearOsConfig::$themes_path . '/' . $framework->session->userdata('theme') . '/' . $app_version;

	$page_head = '';

	if (file_exists(ClearOsConfig::$apps_path . '/' . $js))
		$page_head .= "<script type='text/javascript' src='/approot" . $js . "'></script>\n";

	if (file_exists(ClearOsConfig::$apps_path . '/' . $css))
		$page_head .= "<link type='text/css' href='/approot" . $css ."' rel='stylesheet'>";

	// Write out the head
	//-------------------

	if (file_exists($theme_basepath . '/widgets/doctype.php'))
		require_once($theme_basepath . '/widgets/doctype.php');
	else
		echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>\n";

	echo "
<html dir='" . $framework->session->userdata('textdir') . "'>

<!-- Head Start -->
<head>

<!-- Basic Head Information -->
<title>$title</title>
<meta http-equiv='Content-Type' content='text/html; charset=" . $framework->session->userdata('charset') . "'>

<!-- Jquery -->
<script type='text/javascript' src='/js/jquery-1.4.4.min.js'></script>

";

	if (file_exists($theme_basepath . '/widgets/head.php'))
		require_once($theme_basepath . '/widgets/head.php');

echo "
<!-- Page-specific Head -->
$page_head
</head>
<!-- Head end -->

";
}

/**
 * Returns the page header.
 *
 * @param string $layout page layout
 * @return string HTML head section
 */

function clearos_header($layout, $data)
{
	ClearOsLogger::Profile(__METHOD__, __LINE__);

	$framework =& get_instance();

	$theme_path = ClearOsConfig::GetThemePath($framework->session->userdata('theme'));

	/// FIXME - pass parameters properly
	$title = $data['title'];

	if ($layout == 'default') {

		$default_file = $theme_path . '/widgets/header_default.php';

		if (file_exists($default_file))
			require($default_file);

	} else if ($layout == 'splash') {

		$splash_file = $theme_path . '/widgets/header_splash.php';

		if (file_exists($splash_file))
			require($splash_file);
	}
}

/**
 * Returns the page footer
 *
 * @param string $layout page layout
 * @return string HTML head section
 */

function clearos_footer($layout = 'default')
{
	ClearOsLogger::Profile(__METHOD__, __LINE__);

	$framework =& get_instance();

	$theme_path = ClearOsConfig::GetThemePath($framework->session->userdata('theme'));

	if ($layout == 'default') {

		$default_file = $theme_path . '/widgets/footer_default.php';

		if (file_exists($default_file))
			require($default_file);

	} else if ($layout == 'splash') {

		$default_file = $theme_path . '/widgets/footer_splash.php';

		if (file_exists($default_file))
			require($default_file);
	}
}

///////////////////////////////////////////////////////////////////////////////
// S U M M A R Y  V I E W
///////////////////////////////////////////////////////////////////////////////

function clearos_summary_page($links) {
	echo _clearos_summary_page($links);
}

///////////////////////////////////////////////////////////////////////////////
// A N C H O R S
///////////////////////////////////////////////////////////////////////////////

function anchor_custom($url, $id = NULL, $text)
{
	return _anchor_theme($url, $id, $text, 'anchor-custom');
}

function anchor_add($url, $id = NULL)
{
	return _anchor_theme($url, $id, lang('base_add'), 'anchor-add');
}

function anchor_edit($url, $id = NULL)
{
	return _anchor_theme($url, $id, lang('base_edit'), 'anchor-edit');
}

function anchor_cancel($url, $id = NULL)
{
	return _anchor_theme($url, $id, lang('base_cancel'), 'anchor-cancel');
}

function anchor_home($url, $id = NULL)
{
	return _anchor_theme($url, $id, lang('base_home'), 'anchor-home');
}

function anchor_update($url, $id = NULL)
{
	return _anchor_theme($url, $id, lang('base_update'), 'anchor-update');
}

function anchor_delete($url, $id = NULL)
{
	return _anchor_theme($url, $id, lang('base_delete'), 'anchor-delete');
}

function anchor_previous($url, $id = NULL)
{
	return _anchor_theme($url, $id, lang('base_previous'), 'anchor-previous');
}

function anchor_next($url, $id = NULL)
{
	return _anchor_theme($url, $id, lang('base_next'), 'anchor-next');
}

///////////////////////////////////////////////////////////////////////////////
// B U T T O N S
///////////////////////////////////////////////////////////////////////////////

function form_submit_add($name, $id = NULL)
{
	return _form_submit_theme($name, $id, lang('base_add'), 'form-button-add');
}

function form_submit_delete($name, $id = NULL)
{
	return _form_submit_theme($name, $id, lang('base_delete'), 'form-button-delete');
}

function form_submit_update($name, $id = NULL)
{
	return _form_submit_theme($name, $id, lang('base_update'), 'form-button-update');
}

function form_submit_previous($name, $id = NULL)
{
	return _form_submit_theme($name, $id, lang('base_previous'), 'form-button-previous');
}

function form_submit_next($name, $id = NULL)
{
	return _form_submit_theme($name, $id, lang('base_next'), 'form-button-next');
}

function form_submit_disable($name, $id = NULL)
{
	return _form_submit_theme($name, $id, lang('base_disable'), 'form-button-disable');
}

function form_submit_custom($name, $id, $text)
{
	return _form_submit_theme($name, $id, $text, 'form-button-custom');
}

///////////////////////////////////////////////////////////////////////////////
// B U T T O N  S E T S
///////////////////////////////////////////////////////////////////////////////

function cos_button_set($buttons)
{
	$html = _button_set_open() . $buttons . _button_set_close();

	return $html;
}

function button_set_open()
{
	return _button_set_open();
}

function button_set_close()
{
	return _button_set_close();
}

///////////////////////////////////////////////////////////////////////////////
// R A D I O  S E T S
///////////////////////////////////////////////////////////////////////////////

function form_radio_set_open($class, $orientation)
{
// return "<div data-role='fieldcontain'>
//    <fieldset data-role='controlgroup' data-type='horizontal'>
//";
	return "<div class='$class'>\n";
}

function form_radio_set_item($id, $name, $label, $checked = FALSE)
{
	return "<input type='radio' id='$id' name='$name' /><label for='$id'>$label</label>\n";
//	return "<input type='radio' id='$id' name='$name' /><label for='$id'>$label</label>\n";
}

function form_radio_set_close()
{
//	return "   </fieldset>
// </div>";
	return "</div>\n";
}

///////////////////////////////////////////////////////////////////////////////
// S E L E C T  B O X E S
///////////////////////////////////////////////////////////////////////////////

function cos_form_dropdown($name, $options, $selected, $label)
{
	return _cos_form_dropdown($name, $options, $selected, $label);
}

function cos_form_toggle_enable($name, $default, $label)
{
	$options = array(
		'0' => lang('base_disabled'),
		'1' => lang('base_enabled')
	);

	$selected = set_value($name, $default);

	return _cos_form_toggle($name, $options, $selected, $label);
} 

///////////////////////////////////////////////////////////////////////////////
// I N P U T  B O X E S
///////////////////////////////////////////////////////////////////////////////

function cos_form_input($name, $default, $label, $readonly = FALSE)
{
	// FIXME - allow theming
	if ($readonly) {
		$html = "<div>" . form_label($label, $name) . " <span id='$name'>" . $default . "</span></div>";
	} else {
		$html = "
			<div>" .
				form_label($label, $name) . 
				form_input($name, set_value($name, $default)) . " " .
				form_error($name). "
			</div>";
	}

	return $html;
} 

///////////////////////////////////////////////////////////////////////////////
// S U M M A R Y  T A B L E S
///////////////////////////////////////////////////////////////////////////////

function summary_table_start($title)
{
	return _summary_table_start($title);
}

function summary_table_header($headers)
{
	return  _summary_table_header($headers);
}

function summary_table_items($items)
{
	return _summary_table_items($items);
}

function summary_table_end()
{
	return _summary_table_end();
}

//////////////////////////////////////////////////////////////////////////////
// C O N F I R M A T I O N  D I A L O G B O X
///////////////////////////////////////////////////////////////////////////////

function dialogbox_confirm($message, $ok_anchor, $cancel_anchor)
{
	return _dialogbox_confirm($message, $ok_anchor, $cancel_anchor);
}

///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// C O N S T A N T S
///////////////////////////////////////////////////////////////////////////////

// FIXME: move to session
define('BCONFIG_CONSOLE', intval((substr(getenv("HTTP_USER_AGENT"),0,4) == "Lynx")&($_SERVER['REMOTE_ADDR'] == '127.0.0.1')));
define('BCONFIG_GUI', intval((substr(getenv("HTTP_USER_AGENT"),91,12) == "GranParadiso")&($_SERVER['REMOTE_ADDR'] == '127.0.0.1')));


///////////////////////////////////////////////////////////////////////////////
// I C O N S
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// T A B S
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
//
// WebTab - tab layout support
//
///////////////////////////////////////////////////////////////////////////////

function WebTab($tabtitle, $tabinfo, $active)
{
	require(ClearOsConfig::$htdocs_path . "/templates/" . $_SESSION['system_template'] . "/widgets/tabs.php");
}

///////////////////////////////////////////////////////////////////////////////
// P R O G R E S S  B A R
///////////////////////////////////////////////////////////////////////////////

function progress_bar($id)
{
	// Jquery mobile progress bar was not in alpha, but expected in 1.0 
	return "<div class='progressbar' id='$id'></div>";
}

///////////////////////////////////////////////////////////////////////////////
// I N F O  B O X E S
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
//
// infobox -- generic dialog box routine.
//
// Note: do not be tempted to make this one table -- browsers do not behave!
//
// class:  the CSS class (intro, warning, info)
// title:  the title to put in the box
// icon:   the icon to display on the left hand side of the box
// blurb:  blurb to display in dialog box
//
///////////////////////////////////////////////////////////////////////////////

function infobox($type, $message)
{
	if ($type == 'exception') {
		$class = 'ui-state-error';
		$iconclass = 'ui-icon-alert';
	} else if ($type == 'critical') {
		$class = 'ui-state-error';
		$iconclass = 'ui-icon-alert';
	} else if ($type == 'warning') {
		$class = 'ui-state-highlight';
		$iconclass = 'ui-icon-info';
	} else if ($type == 'highlight') {
		$class = 'ui-state-default';
		$iconclass = 'ui-icon-info';
	} else if ($type == 'help') {
		$class = 'ui-state-default';
		$iconclass = 'ui-icon-help';
	}

	echo "
		<div class='ui-widget'>
			<div class='ui-corner-all $class' style='margin-top: 20px; padding: 0 .7em;'>
				<p><span class='ui-icon $iconclass' style='float: left; margin-right: .3em;'></span>$message</p>
			</div>
		</div>
	";
}

function infobox_exception($message)
{
	infobox('exception', $message);
}

function infobox_critical($message)
{
	infobox('critical', $message);
}

function infobox_warning($message)
{
	infobox('warning', $message);
}

function infobox_highlight($message)
{
	infobox('highlight', $message);
}


function helpbox($message)
{
	// FIXME - make this a standalone widget
	infobox('help', $message);
}


function dialogbox($id, $title, $message)
{
	$dialog = "
<div class='dialogbox' id='$id' title='$title'>
    <p>$message</p>
</div>
";

	return $dialog;
}

///////////////////////////////////////////////////////////////////////////////
//
// WebDialogIntro -- prints out the summary
//
///////////////////////////////////////////////////////////////////////////////

// TODO: $icon is not used anymore
function WebDialogIntro($title, $icon, $summary)
{
	// TODO: make this more efficient
	$pagedata = WebMenuFetch();

	foreach ($pagedata as $info) {
		if ($_SERVER['PHP_SELF'] == $info['url'])
			break;
	}

	$basename = preg_replace("/\/app\//", "", $_SERVER['PHP_SELF']);
	$basename = preg_replace("/\.php/", "", $basename);

	$page['section'] = $info['section'];
	$page['subsection'] = $info['subsection'];
	$page['title'] = $info['title'];
	$page['summary'] = $summary;
	$page['user_guide_url'] = $_SESSION['system_online_help'] . $_SERVER['PHP_SELF'];

	// Allow templates to override icons
	$large_icon = "icon-$basename.png";
	$small_icon = "icon-$basename.png";

	$large_icon_override = ClearOsConfig::$htdocs_path . "/templates/" . $_SESSION['system_template'] . "/images/icons/32x32/" . $large_icon;
	$small_icon_override = ClearOsConfig::$htdocs_path . "/templates/" . $_SESSION['system_template'] . "/images/icons/16x16/" . $small_icon;

	$page['large_icon'] = (file_exists($large_icon_override)) ? $large_icon_override : "/images/icons/32x32/$large_icon";
	$page['small_icon'] = (file_exists($small_icon_override)) ? $small_icon_override : "/images/icons/16x16/$small_icon";

	require(ClearOsConfig::$htdocs_path . "/templates/" . $_SESSION['system_template'] . "/widgets/summary.php");

	if (BCONFIG_CONSOLE)
		echo "<hr>";

	// Flush the buffers here
	flush();
}

///////////////////////////////////////////////////////////////////////////////
//
// WebDialogDaemon -- prints out a dialog box for a particular daemon
//
///////////////////////////////////////////////////////////////////////////////

function WebDialogDaemon($initd, $show_onboot = true)
{
	if (! file_exists(COMMON_CORE_DIR . '/api/Daemon.php'))
		return;

	require_once(COMMON_CORE_DIR . '/api/Daemon.php');

	$daemon = new Daemon($initd);

	if (! $daemon->IsInstalled())
		return ;

	# Give the daemon a couple of seconds to start on a reload or restart.

	$status = false;
	$onboot = false;

	try {
		$status = $daemon->GetRunningState();
		$onboot = $daemon->GetBootState();
	} catch (Exception $e) {
		infobox_warning($e->GetMessage());
		return;
	}

	if ($status) {
		$status_button = WebButtonToggle("StopDaemon", DAEMON_LANG_STOP);
		$status = "<span class='ok'><b>" . DAEMON_LANG_RUNNING . "</b></span>";
	} else {
		$status_button = WebButtonToggle("StartDaemon", DAEMON_LANG_START);
		$status = "<span class='alert'><b>" . DAEMON_LANG_STOPPED . "</b></span>";
	}

	if ($show_onboot) {
		if ($onboot) {
			$onboot_button = WebButtonToggle("DisableBoot", DAEMON_LANG_TO_MANUAL);
			$onboot = "<span class='ok'><b>" . DAEMON_LANG_AUTOMATIC . "</b></span>";
		} else {
			$onboot_button = WebButtonToggle("EnableBoot", DAEMON_LANG_TO_AUTO);
			$onboot = "<span class='alert'><b>" . DAEMON_LANG_MANUAL . "</b></span>";
		}
	}

	// Build sub-table
	//----------------

	$content = "
		<form action='' method='post'>
		<table width='100%' border='0' cellspacing='0' cellpadding='0' align='center'>
			<tr>
				<td nowrap align='right'><b>" . DAEMON_LANG_STATUS . " -</b>&#160; </td>
				<td nowrap><b>$status</b></td>
				<td width='10'>&#160; </td>
				<td width='100'>$status_button</td>
				<td width='10'>&#160; </td>
				<td rowspan='2'>" . DAEMON_LANG_WARNING_START . "</td>
			</tr>
	";

	if ($show_onboot) {
		$content .= "
			<tr>
				<td nowrap align='right'><b>" . DAEMON_LANG_ONBOOT . " -</b>&#160; </td>
				<td nowrap><b>$onboot</b></td>
				<td width='10'>&#160; </td>
				<td nowrap>$onboot_button</td>
				<td width='10'>&#160; </td>
			</tr>
		";
	}

	$content .= "
		</table>
		</form>
	";

	// Use the standard dialog-box
	//----------------------------

	infobox("dialogdaemon", BCONFIG_LANG_SERVER_STATUS, WEBCONFIG_DIALOG_ICON_DAEMON, $content);
}

///////////////////////////////////////////////////////////////////////////////
// C H A R T S
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// M E N U  S Y S T E M
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
//
// WebMenuFetch
//
///////////////////////////////////////////////////////////////////////////////

function WebMenuFetch()
{
//	$webconfig = new Webconfig();

	$devel = array(
		'section' => 'Developer',
		'subsection' => 'Tools',
		'title' => 'Theme',
		'url' => '/app/devel',
		'type' => 'configuration',
		'priority' => '2001'
	);

	$date = array(
		'section' => 'System',
		'subsection' => 'Settings',
		'title' => 'Date',
		'url' => '/app/date',
		'type' => 'configuration',
		'priority' => '2001'
	);

	$dhcp = array(
		'section' => 'System',
		'subsection' => 'Settings',
		'title' => 'DHCP',
		'url' => '/app/dhcp',
		'type' => 'configuration',
		'priority' => '2001'
	);

	$dashboard = array(
		'section' => 'Reports',
		'subsection' => 'Overview',
		'title' => 'Dashboard',
		'url' => '/app/dashboard',
		'type' => 'configuration',
		'priority' => '2001'
	);

	$pagedata = array($devel, $dhcp, $date, $dashboard);

	return $pagedata;
}

///////////////////////////////////////////////////////////////////////////////
//
// Wizard menu system
//
///////////////////////////////////////////////////////////////////////////////

function WebMenuWizard($menuitems, $highlight)
{
	require(ClearOsConfig::$htdocs_path . "/themes/" . $_SESSION['system_template'] . "/widgets/wizard.php");
}

function WebWizardNavigation($action, $previous, $next, $overridenext = null)
{
	echo "<input type='hidden' name='action' value='$action'>";
	echo "<p align='center'>";

	if (! empty($previous))
		echo WebButtonPrevious("GoToPreviousStep[$previous]") . " &nbsp; ";

	if (! empty($next)) {
		if (is_null($overridenext))
			echo WebButtonNext("GoToNextStep[$next]");
		else
			echo _form_submit_theme("GoToNextStep[$next]", $overridenext . (isset($step) ? ' ' . $step : ''), BCONFIG_ICON_NEXT);
	}

	echo "</p>";
	WebFormClose();
}

function WebIsSetup()
{
	if (ereg('^.*\/setup.php$', $_SERVER['PHP_SELF']))
		return true;
	return false;
}

///////////////////////////////////////////////////////////////////////////////
// H A N D Y  S H O R T C U T S
///////////////////////////////////////////////////////////////////////////////

/**
 * Modifies IMG tags for MSIE browsers to fix PNG-24 transparencies.
 *
 * @param  string  $png  path to png image
 * @param  string  $alt  HTML alt parameter for img tag
 * @return  string  HTML img tag
 */

function WebReplacePngTags($png, $alt = "")
{
	// Bail if image isnot a PNG

	if (! preg_match("/\.png/", $png))
		return "<img src='$png' alt='$alt' align='top' />";

	// Bail if this is not IE

	$msie = '/msie\s(5|6)\.?[0-9]*.*(win)/i';

	if (!isset($_SERVER['HTTP_USER_AGENT']) || !preg_match($msie, $_SERVER['HTTP_USER_AGENT']))
		return "<img src='$png' alt='$alt' align='top' />";

	if (!isset($_SERVER['HTTP_USER_AGENT']) || preg_match("/opera/i", $_SERVER['HTTP_USER_AGENT']))
		return "<img src='$png' alt='$alt' align='top' />";

	if (!file_exists(ClearOsConfig::$htdocs_path . "/$png"))
		return "<img src='/images/icon-intro.png' alt='$alt' align='middle' />";

	list($width, $height, $type, $attr) = getimagesize(ClearOsConfig::$htdocs_path . "/$png");

	$png = "<img alt='$alt' align='top' src='/templates/base/images/transparent.png' style='width: $width" . "px; height: $height" . "px; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=$png, sizingMethod=scale)' />";

	return $png;
}

///////////////////////////////////////////////////////////////////////////////
//
// WebDownload
//
///////////////////////////////////////////////////////////////////////////////

function WebDownload($filename)
{
	$ph = popen("/usr/bin/sudo /bin/cat " . escapeshellarg($filename), "r");

	if (!$ph)
		return false;

	$content = "";

	while ($chunk = fread($ph, 4096))
		$content .= $chunk;

	pclose($ph);

	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");
	header("Content-Disposition: attachment; filename=" . basename($filename) . ";");
	header("Content-Transfer-Encoding: binary");

	$length = array_sum(count_chars($content));

	header("Content-Length: ". $length);

	echo $content;

	exit();
}

///////////////////////////////////////////////////////////////////////////////
//
// WebCheckCertificates and WebCheckUserDatabase
//
// On 4.3+ installs, the WebCheckUserDatabase will redirect the user to
// the LDAP setup page.  
//
// On upgrades to 4.3+, the user database is already configured, but
// the SSL certificates may not be.  Similarly, a redirect to the organization
// page is done.
//
// TODO: change this function name to WebCheckCertificatesAndOrganization
// (or something like that).  The Organization check was added during the
// beta and made this messy... clean this up.
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
//
// WebCheckCertificates
//
///////////////////////////////////////////////////////////////////////////////

function WebCheckCertificates()
{
	if (!file_exists(COMMON_CORE_DIR . '/api/Ssl.php'))
		return;

	if (!file_exists(COMMON_CORE_DIR . '/api/Organization.php')) {
		infobox_highlight(LOCALE_LANG_ERRMSG_IRD . " - Organization class is missing");
		WebFooter();
		exit();
	}

	require_once(COMMON_CORE_DIR . '/api/Ssl.php');
	require_once(COMMON_CORE_DIR . '/api/Organization.php');

	// Check for Certificate Authority
	try {
		$ssl = new Ssl();
		$ca_exists = $ssl->ExistsCertificateAuthority();

		// TODO: this is a hack.  We need a way to see if the Organization 
		// information has been properly configured, e.g. we need something
		// like $organization->IsConfigured().

		$organization = new Organization();
		$domain = $organization->GetDomain();
		$orgname = $organization->GetName();
		$street = $organization->GetStreet();
		$city = $organization->GetCity();
		$country = $organization->GetCountry();
	} catch (Exception $e) {
		infobox_warning($e->GetMessage());
	}

	$org_exists = (!empty($domain) && !empty($orgname) && !empty($street) && !empty($city) && !empty($country));

	if (!$ca_exists || !$org_exists) {
		infobox_highlight(LOCALE_LANG_ORGANIZATION_NOT_CONFIGURED . " &#160; " . WebUrlJump("organization.php", LOCALE_LANG_CONFIGURE));
		WebFooter();
		exit();
	}
}

///////////////////////////////////////////////////////////////////////////////
//
// WebCheckUserDatabase
//
///////////////////////////////////////////////////////////////////////////////

function WebCheckUserDatabase()
{
	if (
		(!file_exists(COMMON_CORE_DIR . '/api/ClearDirectory.php')) ||
		(!file_exists(COMMON_CORE_DIR . '/api/Ldap.php'))
		)
		return;

	require_once(COMMON_CORE_DIR . '/api/ClearDirectory.php');
	require_once(COMMON_CORE_DIR . '/api/Ldap.php');

	try {
		$ldap = new Ldap();

		for ($try = 0; $try < 3; $try++) {
			$running = $ldap->GetRunningState();
			if ($running)
				break;
			sleep(1);
		}
	} catch (Exception $e) {
		infobox_warning($e->GetMessage());
		return;
	}

	if (! $running) {
		infobox_warning(LOCALE_LANG_USER_ENGINE_NOT_RUNNING . " &#160; " . WebUrlJump("ldap.php", LOCALE_LANG_CONTINUE));
		WebFooter();
		exit();
	} else {
		try {
			$directory = new ClearDirectory();
			$isinitialized = $directory->IsInitialized();
		} catch (Exception $e) {
			infobox_warning($e->GetMessage());
			return;
		}

		if (! $isinitialized) {
			infobox_highlight(LOCALE_LANG_LDAP_NOT_CONFIGURED . " &#160; " . WebUrlJump("ldap.php", LOCALE_LANG_CONFIGURE));
			WebFooter();
			exit();
		}
	}
}

///////////////////////////////////////////////////////////////////////////////
//
// WebCheckRegistration
//
///////////////////////////////////////////////////////////////////////////////

function WebCheckRegistration()
{
	if (empty($_SESSION['system_registered'])) {
		infobox_warning(LOCALE_LANG_SYSTEM_REGISTRATION_REQUIRED . " - " . WebUrlJump("register.php", LOCALE_LANG_REGISTER));
		WebFooter();
		exit();
	}
}


///////////////////////////////////////////////////////////////////////////////
// U T I L I T I E S
///////////////////////////////////////////////////////////////////////////////

/**
 * Converts a simple array into a hash array
 *
 * In many cases, the key and value in every option in a drop-down list is
 * is identical.  For example, the time zone list from the ClearOS API is
 * just a basic array.  Using:
 * 
 * form_dropdown('timezone', $timezones, $timezone); (mr(dro "form_dropdown($time_o  list drop-down contains:
 *
 * Would yield:
 *
 * - <option value="111">Canada/Eastern</option>
 * - <option value="112">Canada/Mountain</option>
 * - <option value="113">Canada/Newfoundland</option>
 *
 * The key value is not useful.  We really want:
 *
 * - <option value="Canada/Eastern">Canada/Eastern</option>
 * - <option value="Canada/Mountain">Canada/Mountain</option>
 * - <option value="Canada/Newfoundland">Canada/Newfoundland</option>
 *
 */

function convert_to_hash($items) {
    $hash_array = array();

    foreach ($items as $item)
        $hash_array[$item] = $item;

    return $hash_array;
}

?>

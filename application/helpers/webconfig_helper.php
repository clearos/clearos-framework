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
 * @author {@link http://www.foundation.com/ ClearFoundation}
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

clearos_load_library('base/Webconfig');
clearos_load_language('base/base');

///////////////////////////////////////////////////////////////////////////////
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
	// Adding hostname to the title is handy when managing multiple systems
	//---------------------------------------------------------------------

	if ($_SESSION['system_hostname'])
		$title = $_SESSION['system_hostname'] . " - " . $title;

	// Add page-specific head links.  For example, 
	// To support different versions running in parallel, determine the app
	// version e.g. /app/dhcp/htdocs, /app/dhcp/tags/5.1/htdocs, etc.
	//-------------------------------------------------------------------------

	// FIXME: move the versioning stuff to ClearOSCore
	$theme_path = "/themes/" . $_SESSION['system_template'];

	// FIXME: move the versioning stuff to ClearOSCore

	$page_head = '';

	$segments = explode('/', $_SERVER['PHP_SELF']);
	$app = $segments[2];

	if (isset(ClearOsConfig::$clearos_devel_versions['app'][$app]))
		$app_version = ClearOsConfig::$clearos_devel_versions['app'][$app] . '/';
	else if (isset(ClearOsConfig::$clearos_devel_versions['app']['default']))
		$app_version = ClearOsConfig::$clearos_devel_versions['app']['default'] . '/';
	else
		$app_version = "";

	$js_path = '/' . $app . '/' . $app_version . 'htdocs/' . $app . '.js.php';
	$css_path = '/' . $app . '/' . $app_version . 'htdocs/' . $app . '.css';

	if (file_exists(ClearOsConfig::$apps_path . '/' . $js_path))
		$page_head .= "<script type='text/javascript' src='/approot" . $js_path . "'></script>\n";

	if (file_exists(ClearOsConfig::$apps_path . '/' . $css_path))
		$page_head .= "<link type='text/css' href='/approot" . $css_path ."' rel='stylesheet'>";

	// Write out the head
	//-------------------

// FIXME - DOCTYPE must be theme-able
// <!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
// <!DOCTYPE html> 

// FIXME - No JqueryUI?
// <script type='text/javascript' src='/js/jquery-ui-1.8.5.custom.min.js'></script>
// FIXME - Jquery versioning with mobile
// <script type='text/javascript' src='/js/jquery-1.4.2.min.js'></script>

	echo "
<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
<html dir='" . $_SESSION['system_textdir'] . "'>

<!-- HEAD START -->
<head>

<!-- Basic Head Information -->
<title>$title</title>
<meta http-equiv='Content-Type' content='text/html; charset=" . $_SESSION['system_charset'] . "'>

<!-- Jquery Head-->

<!-- Template Head -->
";

	if (file_exists(ClearOsConfig::$themes_path . '/' . $_SESSION['system_template']. '/widgets/head.php'))
		require_once(ClearOsConfig::$themes_path . '/' . $_SESSION['system_template']. '/widgets/head.php');

echo "
<!-- Page-specific Head -->
$page_head
</head>
<!-- HEAD END -->

";
}

/**
 * Returns the page header.
 *
 * @param string $layout page layout
 * @return string HTML head section
 */

function clearos_header($layout)
{
	// FIXME: move the versioning stuff to ClearOSCore
	$theme_path = "/themes/clearos6x/trunk";

	if ($layout == 'default') {
		if (file_exists(ClearOsConfig::$themes_path . '/' . $_SESSION['system_template'] . "/widgets/header_default.php"))
			require(ClearOsConfig::$themes_path . '/' . $_SESSION['system_template'] . "/widgets/header_default.php");
	} else if ($layout == 'splash') {
		if (file_exists(ClearOsConfig::$themes_path . '/' . $_SESSION['system_template'] . "/widgets/header_splash.php"))
			require(ClearOsConfig::$themes_path . '/' . $_SESSION['system_template'] . "/widgets/header_splash.php");
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
	if ($layout == 'default') {
		if (file_exists(ClearOsConfig::$themes_path . '/' . $_SESSION['system_template'] . "/widgets/footer_default.php"))
			require(ClearOsConfig::$themes_path . '/' . $_SESSION['system_template'] . "/widgets/footer_default.php");
	} else if ($layout == 'splash') {
		if (file_exists(ClearOsConfig::$themes_path . '/' . $_SESSION['system_template'] . "/widgets/footer_splash.php"))
			require(ClearOsConfig::$themes_path . '/' . $_SESSION['system_template'] . "/widgets/footer_splash.php");
	}
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
// S E S S I O N
///////////////////////////////////////////////////////////////////////////////

session_cache_expire(30);
session_start();

if (!isset($_SESSION['system_session_started']))
	WebSetSession();

// FIXME
$_SESSION['system_template'] = 'clearos6xmobile/trunk';
$_SESSION['system_template'] = 'clearos6x/trunk';

///////////////////////////////////////////////////////////////////////////////
// G R A P H I C S
///////////////////////////////////////////////////////////////////////////////

// General icons
define('BCONFIG_ICON_ADD', WebSetIcon("icon-add.png"));
define('BCONFIG_ICON_ARROWRIGHT', WebSetIcon("icon-arrowright.png"));
define('BCONFIG_ICON_BACK', WebSetIcon("icon-back.png"));
define('BCONFIG_ICON_CALENDAR', WebSetIcon("icon-calendar.png"));
define('BCONFIG_ICON_CANCEL', WebSetIcon("icon-cancel.png"));
define('BCONFIG_ICON_CHECKMARK', WebSetIcon("icon-checkmark.png"));
define('BCONFIG_ICON_CONFIGURE', WebSetIcon("icon-configure.png"));
define('BCONFIG_ICON_CONTINUE', WebSetIcon("icon-continue.png"));
define('BCONFIG_ICON_DELETE', WebSetIcon("icon-delete.png"));
define('BCONFIG_ICON_DISABLED', WebSetIcon("icon-disabled.png"));
define('BCONFIG_ICON_DOWNLOAD', WebSetIcon("icon-download.png"));
define('BCONFIG_ICON_EDIT', WebSetIcon("icon-edit.png"));
define('BCONFIG_ICON_ENABLED', WebSetIcon("icon-enabled.png"));
define('BCONFIG_ICON_EXTERNAL_LINK', WebSetIcon("icon-external-link.png"));
define('BCONFIG_ICON_FILTER', WebSetIcon("icon-filter.png"));
define('BCONFIG_ICON_GO', WebSetIcon("icon-go.png"));
define('BCONFIG_ICON_HELP', WebSetIcon("icon-help.png"));
define('BCONFIG_ICON_INFO', WebSetIcon("icon-info.png"));
define('BCONFIG_ICON_LOGIN', WebSetIcon("icon-login.png"));
define('BCONFIG_ICON_MINUS', WebSetIcon("icon-minus.png"));
define('BCONFIG_ICON_NEXT', WebSetIcon("icon-next.png"));
define('BCONFIG_ICON_OK', WebSetIcon("icon-ok.png"));
define('BCONFIG_ICON_PLUS', WebSetIcon("icon-plus.png"));
define('BCONFIG_ICON_PREVIOUS', WebSetIcon("icon-previous.png"));
define('BCONFIG_ICON_RENEW', WebSetIcon("icon-renew.png"));
define('BCONFIG_ICON_REPORT', WebSetIcon("icon-report.png"));
define('BCONFIG_ICON_SAVE', WebSetIcon("icon-save.png"));
define('BCONFIG_ICON_SEARCH', WebSetIcon("icon-search.png"));
define('BCONFIG_ICON_STATUS', WebSetIcon("icon-status.png"));
define('BCONFIG_ICON_SUPPORT', WebSetIcon("icon-support.png"));
define('BCONFIG_ICON_TOGGLE', WebSetIcon("icon-toggle.png"));
define('BCONFIG_ICON_UPDATE', WebSetIcon("icon-update.png"));
define('BCONFIG_ICON_USERGUIDE', WebSetIcon("icon-userguide.png"));
define('BCONFIG_ICON_VIEW', WebSetIcon("icon-view.png"));
define('BCONFIG_ICON_WARNING', WebSetIcon("icon-warning.png"));
define('BCONFIG_ICON_XMARK', WebSetIcon("icon-xmark.png"));

// Ajax loading whirlgig
define('BCONFIG_ICON_LOADING', WebSetIcon("icon-loading.gif"));

// TODO -- need to revisit these 4 icons
define('BCONFIG_ICON_INBOUND', WebSetIcon("icon-inbound.png"));
define('BCONFIG_ICON_OUTBOUND', WebSetIcon("icon-outbound.png"));
define('BCONFIG_ICON_UP', WebSetIcon("icon-plus.png"));
define('BCONFIG_ICON_DOWN', WebSetIcon("icon-minus.png"));

// Common applications
// TODO: these need to be pluginable
define('BCONFIG_ICON_EMAIL', WebSetIcon("icon-email.png"));
define('BCONFIG_ICON_GOOGLE_APPS', WebSetIcon("icon-google-apps.gif"));
define('BCONFIG_ICON_FTP', WebSetIcon("icon-ftp.png"));
define('BCONFIG_ICON_OPENVPN', WebSetIcon("icon-openvpn.png"));
define('BCONFIG_ICON_PPTP', WebSetIcon("icon-pptpd.png"));
define('BCONFIG_ICON_PROXY', WebSetIcon("icon-proxy.png"));
define('BCONFIG_ICON_SAMBA', WebSetIcon("icon-samba.png"));
define('BCONFIG_ICON_WEB', WebSetIcon("icon-web.png"));

// FIXME -- need to create these 2 icons
define('BCONFIG_ICON_SHELL', WebSetIcon("icon-shell.png"));
define('BCONFIG_ICON_PBX', WebSetIcon("icon-pbx.png"));

// Dialog box icons
// FIXME -- change these out -- sitting in webconfig/htdocs/templates/base/images/icons/16x16
define('BCONFIG_DIALOG_ICON_DAEMON', WebSetIcon('dialog_icon_daemon.png', false));
define('BCONFIG_DIALOG_ICON_INFO', WebSetIcon('dialog_icon_info.png', false));
define('BCONFIG_DIALOG_ICON_REPORTS', WebSetIcon('dialog_icon_reports.png', false));
define('BCONFIG_DIALOG_ICON_SAVED', WebSetIcon('dialog_icon_saved.png', false));
define('BCONFIG_DIALOG_ICON_WARNING', WebSetIcon('dialog_icon_warning.png', false));


///////////////////////////////////////////////////////////////////////////////
// I C O N S
///////////////////////////////////////////////////////////////////////////////

/**
 * A function to set the path for the img tag for icons.
 */

function WebSetIcon($icon, $is_imgtag = true)
{
	if (isset($_SESSION['system_template']) && file_exists(ClearOsConfig::$htdocs_path . "/templates/" . $_SESSION['system_template'] . "/images/icons/16x16/$icon"))
		$template = $_SESSION['system_template'];
	else
		$template = "base";

	if ($is_imgtag)
		return WebReplacePngTags("/templates/$template/images/icons/16x16/$icon");
	else
		return "/templates/$template/images/icons/16x16/$icon";
}

///////////////////////////////////////////////////////////////////////////////
// A N C H O R S
///////////////////////////////////////////////////////////////////////////////

function _anchor_generic($url, $text, $class, $id = null)
{
	$id = isset($id) ? " id='$id'" : '';
	
//	return "<a href='$url' class='anchor $class' $id>$text</a>";

	// FIXME: Mobile
	return "<a href='$url' class='anchor $class' $id data-role='button' data-inline='true'>$text</a>";
}

function anchor_custom($url, $id, $text, $class = 'anchor-generic')
{
	return _anchor_generic($url, $text, $class, $id);
}

function anchor_add($url, $id)
{
	return _anchor_generic($url, lang('base_add'), 'anchor-add', $id);
}

function anchor_update($url)
{
	return _anchor_generic($url, lang('base_update'), 'anchor-update');
}

function anchor_delete($url)
{
	return _anchor_generic($url, lang('base_delete'), 'anchor-delete');
}

function anchor_previous($url)
{
	return _anchor_generic($url, lang('base_previous'), 'anchor-previous');
}

function anchor_next($url)
{
	return _anchor_generic($url, lang('base_next'), 'anchor-next');
}


///////////////////////////////////////////////////////////////////////////////
// B U T T O N S
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
//
// button -- displays a form button.
//
// name:   the button name
// value:  the button value
// image:  the button image
//
// Developer note: to keep backwards compatibility, this function is now ugly.
// Sorry about that.
//
///////////////////////////////////////////////////////////////////////////////

function form_button_generic($name, $text, $class, $id = null, $options = null)
{
	$optionlist = '';

	if (! empty($options)) {
		foreach ($options as $key => $value)
			$optionlist .= " $key='$value'";
	}

	if (empty($options['type']))
		$optionlist .= " type='submit'";

	if (file_exists(ClearOsConfig::$themes_path . '/' . $_SESSION['system_template'] . "/widgets/button.php")) {
		require(ClearOsConfig::$themes_path . '/' . $_SESSION['system_template'] . "/widgets/button.php");
		return $button;
	} else {
		return "<div>button widget has not been defined</div>";
	}
}

function form_button_add($name, $options = null)
{
	return form_button_generic($name, lang('base_add'), 'form-button-add', $options);
}

function form_button_delete($name, $options = null)
{
	return form_button_generic($name, lang('base_delete'), 'form-button-delete', $options);
}

function form_button_update($name, $step = null, $options = null)
{
	return form_button_generic($name, lang('base_update'), 'form-button-update', $options);
}

function form_button_previous($name, $options = null)
{
	return form_button_generic($name, lang('base_previous'), 'form-button-previous', $options);
}

function form_button_next($name, $step = null, $options = null)
{
	return form_button_generic($name, lang('base_next'), 'form-button-next', $options);
}

function form_button_disable($name, $step = null, $options = null)
{
	return form_button_generic($name, lang('base_disable'), 'form-button-disable', $options);
}

function WebButtonCreate($name, $options = null)
{
	return form_button_generic($name, LOCALE_LANG_CREATE, BCONFIG_ICON_ADD, $options);
}

function WebButtonDelete($name, $options = null)
{
	return form_button_generic($name, LOCALE_LANG_DELETE, BCONFIG_ICON_DELETE, $options);
}

function WebButtonDownload($name, $options = null)
{
	return form_button_generic($name, LOCALE_LANG_DOWNLOAD, BCONFIG_ICON_DOWNLOAD, $options);
}

function WebButtonGenerate($name, $options = null)
{
	return form_button_generic($name, LOCALE_LANG_GENERATE, BCONFIG_ICON_UPDATE, $options);
}

function WebButtonEdit($name, $options = null)
{
	return form_button_generic($name, LOCALE_LANG_EDIT, BCONFIG_ICON_EDIT, $options);
}

function WebButtonGo($name, $options = null)
{
	return form_button_generic($name, LOCALE_LANG_GO, BCONFIG_ICON_GO, $options);
}

function WebButtonToggle($name, $text, $options = null)
{
	return form_button_generic($name, $text, BCONFIG_ICON_TOGGLE, $options);
}

function WebButtonRefresh($name, $options = null)
{
	return form_button_generic($name, LOCALE_LANG_REFRESH, BCONFIG_ICON_UPDATE, $options);
}

function WebButtonReset($name, $options = null)
{
	return form_button_generic($name, LOCALE_LANG_RESET, BCONFIG_ICON_TOGGLE, $options);
}

function WebButtonSelect($name, $options = null)
{
	return form_button_generic($name, LOCALE_LANG_SELECT, BCONFIG_ICON_CONTINUE, $options);
}

function WebButtonShowFullReport($name, $options = null)
{
	return form_button_generic($name, LOCALE_LANG_SHOW_FULL_REPORT, BCONFIG_ICON_CONTINUE, $options);
}

function WebButtonUpdate($name, $options = null)
{
	return form_button_generic($name, LOCALE_LANG_UPDATE, BCONFIG_ICON_UPDATE, $options);
}

function WebButtonConfirm($name, $options = null)
{
	return form_button_generic($name, LOCALE_LANG_CONFIRM, BCONFIG_ICON_CHECKMARK, $options);
}

function WebButtonContinue($name, $options = null)
{
	return form_button_generic($name, LOCALE_LANG_CONTINUE, BCONFIG_ICON_CONTINUE, $options);
}

function WebButtonCancel($name, $options = null)
{
	return form_button_generic($name, LOCALE_LANG_CANCEL, BCONFIG_ICON_CANCEL, $options);
}

function WebButtonLogin($name, $options = null)
{
	return form_button_generic($name, LOCALE_LANG_LOGIN, BCONFIG_ICON_LOGIN, $options);
}

function WebButtonView($name, $options = null)
{
	return form_button_generic($name, LOCALE_LANG_VIEW, BCONFIG_ICON_VIEW, $options);
}

function WebButtonConfigure($name, $options = null)
{
	return form_button_generic($name, LOCALE_LANG_CONFIGURE, BCONFIG_ICON_CONFIGURE, $options);
}

function WebButtonSave($name, $options = null)
{
	return form_button_generic($name, LOCALE_LANG_SAVE, BCONFIG_ICON_SAVE, $options);
}

function WebButtonRenew($name, $options = null)
{
	return form_button_generic($name, LOCALE_LANG_RENEW, BCONFIG_ICON_RENEW, $options);
}

function WebButtonSearch($name, $options = null)
{
	return form_button_generic($name, LOCALE_LANG_SEARCH, BCONFIG_ICON_SEARCH, $options);
}

function WebButtonFilter($name, $options = null)
{
	return form_button_generic($name, LOCALE_LANG_FILTER, BCONFIG_ICON_FILTER, $options);
}


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
// S E L E C T  M E N U
///////////////////////////////////////////////////////////////////////////////

function cos_form_dropdown($name, $options)
{
	// Jquery mobile
	return form_dropdown($name, $options);
/*
	return "
		<div data-role='fieldcontain'>
			<label for='select-choice-1' class='select'>Choose shipping method:</label>
			<select name='select-choice-1' id='select-choice-1'>
				<option value='standard'>Standard: 7 day</option>
				<option value='rush'>Rush: 3 days</option>
				<option value='express'>Express: next day</option>
				<option value='overnight'>Overnight</option>
			</select>
		</div>
	";
*/
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
	if ($type == 'critical') {
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

function WebChartLegend($title, $rows, $headers = "", $width = "100%")
{
	require(ClearOsConfig::$htdocs_path . "/templates/" . $_SESSION['system_template'] . "/widgets/chartlegend.php");

	return $legend;
}

function WebChart($title, $type, $width, $height, $data, $series_color, $bgcolor, $explode, $url='')
{
	require(ClearOsConfig::$htdocs_path . "/templates/" . $_SESSION['system_template'] . "/widgets/chart.php");
}

///////////////////////////////////////////////////////////////////////////////
// A U T H E N T I C A T I O N  A N D  S E S S I O N
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

function WebAuthenticate()
{
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

///////////////////////////////////////////////////////////////////////////////
//
// Session functions
//
///////////////////////////////////////////////////////////////////////////////

/**
 * Sets session variables.
 */

function WebSetSession()
{
	$webconfig = new Webconfig();

	// Hostname
	//---------

	$realhostname = "";

	if (file_exists(COMMON_CORE_DIR . "/api/Hostname.php")) {
		require_once(COMMON_CORE_DIR . "/api/Hostname.php");

		try {
			$hostname = new Hostname();
			$realhostname = $hostname->Get();
		} catch (Exception $e) {
			// Use default
		}
	}

	// Check registration
	//-------------------

	$registered = false;

	if (file_exists(COMMON_CORE_DIR . "/api/Register.php")) {
		require_once(COMMON_CORE_DIR . "/api/Register.php");

		try {
			$register = new Register();
			$registered = $register->GetStatus();
		} catch (Exception $e) {
			// Use default
		}
	}

	// Language
	//---------

	$code = "en_US";
	$charset = 'utf-8';
	$textdir = 'LTR';

	if (file_exists(COMMON_CORE_DIR . "/api/Locale.php")) {
		require_once(COMMON_CORE_DIR . "/api/Locale.php");

		try {
			$locale = new Locale();
			$code = $locale->GetLanguageCode();
			$charset = $locale->GetCharacterSet();
			$textdir = $locale->GetTextDirection();
		} catch (Exception $e) {
			// Use default
		}
	}

	setlocale(LC_ALL, $code);

	// Product Info
	//-------------

	$osname = "Linux";
	$osversion = "2.6";
	$redirect = "";

	if (file_exists(COMMON_CORE_DIR . "/api/Product.php")) {
		require_once(COMMON_CORE_DIR . "/api/Product.php");

		try {
			$product = new Product();
			$osname = $product->GetName();
			$osversion = $product->GetVersion();
			$redirect = $product->GetRedirectUrl() . "/" . preg_replace("/ /", "_", $osname) . "/" . $osversion;
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

	$hostkey = "hostkey";

	if (file_exists(COMMON_CORE_DIR . "/api/Suva.php")) {
		require_once(COMMON_CORE_DIR . "/api/Suva.php");

		try {
			$suva = new Suva();
			$hostkey = $suva->GetHostkey();
		} catch (Exception $e) {
			// Use default
		}
	}

	// Template
	//---------

	$template = "clearos6x";

	if (file_exists(COMMON_CORE_DIR . "/api/Webconfig.php")) {
		require_once(COMMON_CORE_DIR . "/api/Webconfig.php");

		try {
			// $template = $webconfig->GetTemplate();
		} catch (Exception $e) {
			// Use default
		}
	}

	$sdnredirect = "https://secure.clearcenter.com/redirect";

	// Set the session
	//----------------

	if (isset($_SESSION['system_session_started'])) {
		$_SESSION['system_registered'] = $registered;
		$_SESSION['system_online_help'] = $redirect . "/userguide";
		$_SESSION['system_redirect'] = $redirect;
		$_SESSION['system_sdn_redirect'] = $sdnredirect;
		$_SESSION['system_hostkey'] = $hostkey;
		$_SESSION['system_template'] = $template;
		$_SESSION['system_locale'] = $code;
		$_SESSION['system_charset'] = $charset;
		$_SESSION['system_textdir'] = $textdir;
		$_SESSION['system_osname'] = $osname;
		$_SESSION['system_osversion'] = $osversion;
		$_SESSION['system_hostname'] = $realhostname;
	} else {
		$_SESSION = array(
				'system_session_started' => true,
				'system_registered' => $registered,
				'system_online_help' => $redirect . "/userguide",
				'system_redirect' => $redirect,
				'system_sdn_redirect' => $sdnredirect,
				'system_hostkey' => $hostkey,
				'system_template' => $template,
				'system_locale' => $code,
				'system_charset' => $charset,
				'system_textdir' => $textdir,
				'system_osname' => $osname,
				'system_osversion' => $osversion,
				'system_hostname' => $realhostname
		);
	}
}

/**
 * Sets session variables when authenticated.
 */

function WebSetSessionAuthenticated()
{
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

///////////////////////////////////////////////////////////////////////////////
//
// WebForwardPage
//
// Forward a request to a new page -- this must be called before anything
// is sent to the web browser.
//
///////////////////////////////////////////////////////////////////////////////

function WebForwardPage($page)
{
	if (BCONFIG_CONSOLE)
		header("Location: http://127.0.0.1:82/$page");
	else
		header("Location: $page");
}

function WebUrlJump($url, $description)
{
	return "<a href='$url'>$description " . BCONFIG_ICON_CONTINUE . "</a>";
}

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
	$webconfig = new Webconfig();

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
			echo form_button_generic("GoToNextStep[$next]", $overridenext . (isset($step) ? ' ' . $step : ''), BCONFIG_ICON_NEXT);
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

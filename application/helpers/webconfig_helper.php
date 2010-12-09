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

// clearos_load_language('base');

// FIXME
define('CLEAROS_MOBILE', 'mobile');

///////////////////////////////////////////////////////////////////////////////
// A N C H O R S
///////////////////////////////////////////////////////////////////////////////

/**
 * Common function for all anchor_* calls.
 */

function _anchor_common($url, $text, $importance, $class, $options = NULL)
{
	// FIXME
	$id = (isset($options['id'])) ? $options['id'] : preg_replace('/\//', '', $url);
	// $class = ...

	// Call theme hook
	return "\t" . _anchor($url, $text, $importance, $class, $id);
}

/**
 * Custom anchors
 */

function anchor_custom($url, $text, $importance, $options = NULL)
{
	return _anchor_common($url, $text, $importance, 'anchor-custom', $options);
}

/**
 * Javascript anchor
 */

function anchor_javascript($id, $text, $importance, $options = NULL)
{
	$options['id'] = $id;

	return _anchor_common('#', $text, $importance, 'anchor-javascript', $options);
}

/**
 * Standard anchors
 */

function anchor_add($url, $importance = 'high', $options = NULL)
{
	return _anchor_common($url, lang('base_add'), $importance, 'anchor-add', $options);
}

function anchor_edit($url, $importance = 'high', $options = NULL)
{
	return _anchor_common($url, lang('base_edit'), $importance, 'anchor-edit', $options);
}

function anchor_cancel($url, $importance = 'low', $options = NULL)
{
	return _anchor_common($url, lang('base_cancel'), $importance, 'anchor-cancel', $options);
}

function anchor_update($url, $importance = 'high', $options = NULL)
{
	return _anchor_common($url, lang('base_update'), $importance, 'anchor-update', $options);
}

function anchor_delete($url, $importance = 'low', $options = NULL)
{
	return _anchor_common($url, lang('base_delete'), $importance, 'anchor-delete', $options);
}

function anchor_previous($url, $importance = 'high', $options = NULL)
{
	return _anchor_common($url, lang('base_previous'), $importance, 'anchor-previous', $options);
}

function anchor_next($url, $importance = 'high', $options = NULL)
{
	return _anchor_common($url, lang('base_next'), $importance, 'anchor-next', $options);
}

///////////////////////////////////////////////////////////////////////////////
// B U T T O N S
///////////////////////////////////////////////////////////////////////////////

/**
 * Common function for all form_submit_* calls.
 */

function _form_submit_common($name, $text, $importance, $class, $options = NULL)
{
	// FIXME
	$id = (isset($options['id'])) ? $options['id'] : "FIXME_$name";
	// $class = ...

	// Call theme hook
	return "\t" . _form_submit($name, $text, $importance, $class, $id);
}

/**
 * Custom submit buttons
 */

function form_submit_custom($name, $text, $importance, $options = NULL)
{
	return _form_submit_common($name, $text, $importance, 'form-button-custom', $options);
}

/**
 * Standard submit buttons
 */

function form_submit_add($name, $importance = 'high', $options = NULL)
{
	return _form_submit_common($name, lang('base_add'), $importance, 'form-button-add', $options);
}

function form_submit_delete($name, $importance = 'low', $options = NULL)
{
	return _form_submit_common($name, lang('base_delete'), $importance, 'form-button-delete', $options);
}

function form_submit_update($name, $importance = 'high', $options = NULL)
{
	return _form_submit_common($name, lang('base_update'), $importance, 'form-button-update', $options);
}

function form_submit_previous($name, $importance = 'high', $options = NULL)
{
	return _form_submit_common($name, lang('base_previous'), $importance, 'form-button-previous', $options);
}

function form_submit_next($name, $importance = 'high', $options = NULL)
{
	return _form_submit_common($name, lang('base_next'), $importance, 'form-button-next', $options);
}

function form_submit_disable($name, $importance = 'low', $options = NULL)
{
	return _form_submit_common($name, lang('base_disable'), $importance, 'form-button-disable', $options);
}

///////////////////////////////////////////////////////////////////////////////
// B U T T O N  S E T S
///////////////////////////////////////////////////////////////////////////////

function button_set($buttons, $options = NULL)
{
	// FIXME
	$id = (isset($options['id'])) ? $options['id'] : 'button_set_FIXME';

	$html = _button_set_open($id) . $buttons . _button_set_close();

	return $html;
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
}

function form_radio_set_close()
{
//	return "   </fieldset>
// </div>";
	return "</div>\n";
}

///////////////////////////////////////////////////////////////////////////////
// I N P U T  B O X E S
///////////////////////////////////////////////////////////////////////////////

function field_input($name, $default, $label, $readonly = FALSE, $options = NULL)
{
	$id = (isset($options['id'])) ? $options['id'] : $name;

	if ($readonly) {
		$html = "<div>\n";
		$html .= "\t" . _form_label($label, $name);
		$html .= "\t" . _form_value($default, $id);
		$html .= "</div>\n";
	} else {
		$html = "<div>\n";
		$html .= "\t" . _form_label($label, $name);
		$html .= "\t" . _form_input($name, set_value($name, $default), $id) . form_error($name);
		$html .= "</div>\n";
	}

	return $html;
} 

///////////////////////////////////////////////////////////////////////////////
// S E L E C T  B O X E S
///////////////////////////////////////////////////////////////////////////////

function field_dropdown($name, $values, $selected, $label, $readonly = FALSE, $options = NULL)
{
	$id = (isset($options['id'])) ? $options['id'] : $name;

	if ($readonly) {
		$html = "<div>\n";
		$html .= "\t" . _form_label($label, $name);
		$html .= "\t" . _form_value($default, $id);
		$html .= "</div>\n";
	} else {
		$html = "<div>\n";
		$html .= "\t" . _form_label($label, $name);
		$html .= "\t" . _form_dropdown_start($name, $id);

		foreach ($values as $option) {
			$is_selected = ($selected == $option) ? 'selected' : '';
			$html .= "\t\t<option value='$option' $is_selected>$option</option>\n";
		}

		$html .= "\t" . _form_dropdown_end();
		$html .= "</div>\n";
	}

	return $html;
}

///////////////////////////////////////////////////////////////////////////////
// T O G G L E  B O X E S
///////////////////////////////////////////////////////////////////////////////

function field_toggle_enable_disable($name, $default, $label, $readonly = FALSE, $options = NULL)
{
	$id = (isset($options['id'])) ? $options['id'] : $name;

	$values = array(
		'0' => lang('base_disabled'),
		'1' => lang('base_enabled')
	);

	if ($readonly) {
		$value = $values[$default];

		$html = "<div>\n";
		$html .= "\t" . _form_label($label, $name);
		$html .= "\t" . _form_value($value, $id);
		$html .= "</div>\n";
	} else {
		$selected_value = set_value($name, $default);

		$html = "<div>\n";
		$html .= "\t" . _form_label($label, $name);
		$html .= "\t" . _form_toggle_start($name, $id);

		foreach ($values as $value => $text) {
			$selected = ($selected_value == $value) ? ' selected' : '';
			$html .= "\t\t<option value='$value'$selected>$text</option>\n";
		}

		$html .= "\t" . _form_toggle_end();
		$html .= "</div>\n";
	}

	return $html;
} 

///////////////////////////////////////////////////////////////////////////////
// S U M M A R Y  V I E W
///////////////////////////////////////////////////////////////////////////////

function clearos_summary_page($links) {
	echo _clearos_summary_page($links);
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

	return "
		<div class='ui-widget'>
			<div class='ui-corner-all $class' style='margin-top: 20px; padding: 0 .7em;'>
				<p><span class='ui-icon $iconclass' style='float: left; margin-right: .3em;'></span>$message</p>
			</div>
		</div>
	";
}

function infobox_exception($message)
{
	return infobox('exception', $message);
}

function infobox_critical($message)
{
	return infobox('critical', $message);
}

function infobox_warning($message)
{
	return infobox('warning', $message);
}

function infobox_highlight($message)
{
	return infobox('highlight', $message);
}


function helpbox($message)
{
	// FIXME - make this a standalone widget
	return infobox('help', $message);
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

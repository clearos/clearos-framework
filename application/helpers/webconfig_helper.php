<?php

/**
 * Webconfig helper.
 *
 * The Webconfig helper is used to give the web-interface a consistent look and feel.
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

// clearos_load_language('base');

// FIXME
define('CLEAROS_MOBILE', 'mobile');

///////////////////////////////////////////////////////////////////////////////
// A N C H O R S
///////////////////////////////////////////////////////////////////////////////

/**
 * Common function for all anchor_* calls.
 */

function _anchor_common($url, $text, $importance, $class, $attributes = NULL)
{
    $id = (isset($attributes['id'])) ? $attributes['id'] : 'clearos' . mt_rand();

    // Call theme hook
    return "\t" . theme_anchor($url, $text, $importance, $class, $id);
}

/**
 * Custom anchors
 */

function anchor_custom($url, $text, $importance, $attributes = NULL)
{
    return _anchor_common($url, $text, $importance, 'clearos-anchor-custom', $attributes);
}

/**
 * Javascript anchor
 */

function anchor_javascript($id, $text, $importance, $attributes = NULL)
{
    $attributes['id'] = $id;

    return _anchor_common('#', $text, $importance, 'clearos-anchor-javascript', $attributes);
}

/**
 * Standard anchors
 */

function anchor_add($url, $importance = 'high', $attributes = NULL)
{
    return _anchor_common($url, lang('base_add'), $importance, 'clearos-anchor-add', $attributes);
}

function anchor_edit($url, $importance = 'high', $attributes = NULL)
{
    return _anchor_common($url, lang('base_edit'), $importance, 'clearos-anchor-edit', $attributes);
}

function anchor_cancel($url, $importance = 'low', $attributes = NULL)
{
    return _anchor_common($url, lang('base_cancel'), $importance, 'clearos-anchor-cancel', $attributes);
}

function anchor_delete($url, $importance = 'low', $attributes = NULL)
{
    return _anchor_common($url, lang('base_delete'), $importance, 'clearos-anchor-delete', $attributes);
}

function anchor_ok($url, $importance = 'high', $attributes = NULL)
{
    return _anchor_common($url, lang('base_ok'), $importance, 'clearos-anchor-ok', $attributes);
}

function anchor_previous($url, $importance = 'high', $attributes = NULL)
{
    return _anchor_common($url, lang('base_previous'), $importance, 'clearos-anchor-previous', $attributes);
}

function anchor_next($url, $importance = 'high', $attributes = NULL)
{
    return _anchor_common($url, lang('base_next'), $importance, 'clearos-anchor-next', $attributes);
}

function anchor_view($url, $importance = 'high', $attributes = NULL)
{
    return _anchor_common($url, lang('base_view'), $importance, 'clearos-anchor-view', $attributes);
}

///////////////////////////////////////////////////////////////////////////////
// B U T T O N S
///////////////////////////////////////////////////////////////////////////////

/**
 * Common function for all form_submit_* calls.
 */

function _form_submit_common($name, $text, $importance, $class, $attributes = NULL)
{
    // FIXME
    $id = (isset($attributes['id'])) ? $attributes['id'] : "FIXME_$name";
    // $class = ...

    // Call theme hook
    return "\t" . theme_form_submit($name, $text, $importance, $class, $id);
}

/**
 * Custom submit buttons
 */

function form_submit_custom($name, $text, $importance, $attributes = NULL)
{
    return _form_submit_common($name, $text, $importance, 'clearos-form-submit-custom', $attributes);
}

/**
 * Standard submit buttons
 */

function form_submit_add($name, $importance = 'high', $attributes = NULL)
{
    return _form_submit_common($name, lang('base_add'), $importance, 'clearos-form-submit-add', $attributes);
}

function form_submit_delete($name, $importance = 'low', $attributes = NULL)
{
    return _form_submit_common($name, lang('base_delete'), $importance, 'clearos-form-submit-delete', $attributes);
}

function form_submit_update($name, $importance = 'high', $attributes = NULL)
{
    return _form_submit_common($name, lang('base_update'), $importance, 'clearos-form-submit-update', $attributes);
}

function form_submit_previous($name, $importance = 'high', $attributes = NULL)
{
    return _form_submit_common($name, lang('base_previous'), $importance, 'clearos-form-submit-previous', $attributes);
}

function form_submit_next($name, $importance = 'high', $attributes = NULL)
{
    return _form_submit_common($name, lang('base_next'), $importance, 'clearos-form-submit-next', $attributes);
}

function form_submit_disable($name, $importance = 'low', $attributes = NULL)
{
    return _form_submit_common($name, lang('base_disable'), $importance, 'clearos-form-submit-disable', $attributes);
}

///////////////////////////////////////////////////////////////////////////////
// B U T T O N  S E T S
///////////////////////////////////////////////////////////////////////////////

function button_set($buttons, $attributes = NULL)
{
    // FIXME
    $id = (isset($attributes['id'])) ? $attributes['id'] : 'clearos' . mt_rand();

    $html = theme_button_set($buttons, $id);

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
//    return "   </fieldset>
// </div>";
    return "</div>\n";
}

///////////////////////////////////////////////////////////////////////////////
// F I E L D  V I E W
///////////////////////////////////////////////////////////////////////////////

function field_view($name, $value, $label, $ids = NULL)
{
    // An input ID is required for the label.  See why @
    // http://www.clearfoundation.com/docs/developer/framework/widgets/field_class_-_why

    $input_id = (isset($ids['input'])) ? $ids['input'] : 'clearos' . mt_rand();

    $html = theme_field_view($value, $label, $input_id, $ids);

    return $html;
} 

///////////////////////////////////////////////////////////////////////////////
// F I E L D  I N P U T
///////////////////////////////////////////////////////////////////////////////

function field_input($name, $default, $label, $readonly = FALSE, $ids = NULL)
{
    // An input ID is required for the label.  See why @
    // http://www.clearfoundation.com/docs/developer/framework/widgets/field_class_-_why

    $input_id = (isset($ids['input'])) ? $ids['input'] : 'clearos' . mt_rand();

    $value = ($readonly) ? $default : set_value($name, $default);
    $error = form_error($name);

    if ($readonly)
        $html = theme_field_view($value, $label, $input_id, $ids);
    else
        $html = theme_field_input($name, $value, $label, $error, $input_id, $ids);

    return $html;
} 

///////////////////////////////////////////////////////////////////////////////
// F I E L D  P A S S W O R D
///////////////////////////////////////////////////////////////////////////////
// TODO: merge with field_input

function field_password($name, $default, $label, $readonly = FALSE, $ids = NULL)
{
    // An input ID is required for the label.  See why @
    // http://www.clearfoundation.com/docs/developer/framework/widgets/field_class_-_why

    $input_id = (isset($ids['input'])) ? $ids['input'] : 'clearos' . mt_rand();

    $value = ($readonly) ? $default : set_value($name, $default);
    $error = form_error($name);

    if ($readonly)
        $html = theme_field_view($value, $label, $input_id, $ids);
    else
        $html = theme_field_password($name, $value, $label, $error, $input_id, $ids);

    return $html;
} 

///////////////////////////////////////////////////////////////////////////////
// F I E L D  D R O P D O W N
///////////////////////////////////////////////////////////////////////////////

function field_dropdown($name, $options, $default, $label, $readonly = FALSE, $ids = NULL)
{
    $selected = ($readonly) ? $default : set_value($name, $default);
    $error = form_error($name);

    $input_id = (isset($ids['input'])) ? $ids['input'] : 'clearos' . mt_rand();

    if ($readonly)
        $html = theme_field_view($options[$default], $label, $input_id, $ids);
    else
        $html = theme_field_dropdown($name, $selected, $label, $error, $options, $input_id, $ids);

    return $html;
}

///////////////////////////////////////////////////////////////////////////////
// F I E L D  S I M P L E  D R O P D O W N
///////////////////////////////////////////////////////////////////////////////

function field_simple_dropdown($name, $options, $default, $label, $readonly = FALSE, $ids = NULL)
{
    $selected = ($readonly) ? $default : set_value($name, $default);
    $error = form_error($name);

    $options = convert_to_hash($options);

    $input_id = (isset($ids['input'])) ? $ids['input'] : 'clearos' . mt_rand();

    if ($readonly)
        $html = theme_field_view($default, $label, $input_id, $ids);
    else
        $html = theme_field_dropdown($name, $selected, $label, $error, $options, $input_id, $ids);

    return $html;
}


///////////////////////////////////////////////////////////////////////////////
// F I E L D  T O G G L E
///////////////////////////////////////////////////////////////////////////////

function field_toggle_enable_disable($name, $default, $label, $readonly = FALSE, $ids = NULL)
{
    $selected = ($readonly) ? $default : set_value($name, $default);
    $error = form_error($name);

    $input_id = (isset($ids['input'])) ? $ids['input'] : 'clearos' . mt_rand();

    $options = array(
        '0' => lang('base_disabled'),
        '1' => lang('base_enabled')
    );

    if ($readonly) {
        $value = $options[$default];
        $html = theme_field_view($value, $label, $input_id, $ids);
    } else {
        $html = theme_field_toggle_enable_disable($name, $selected, $label, $error, $options, $input_id, $ids);
    }

    return $html;
} 

///////////////////////////////////////////////////////////////////////////////
// F I E L D  C H E C K B O X E S
///////////////////////////////////////////////////////////////////////////////

function field_checkbox($name, $default, $label, $readonly = FALSE, $ids = NULL)
{
    // An input ID is required for the label.  See why @
    // http://www.clearfoundation.com/docs/developer/framework/widgets/field_class_-_why

    $input_id = (isset($ids['input'])) ? $ids['input'] : 'clearos' . mt_rand();

    $value = ($readonly) ? $default : set_value($name, $default);
    $error = form_error($name);

    if ($readonly)
        $html = theme_field_view($value, $label, $input_id, $ids);
    else
        $html = theme_field_checkbox($name, $value, $label, $error, $input_id, $ids);

    return $html;
} 

///////////////////////////////////////////////////////////////////////////////
// F I E L D  P R O G R E S S  B A R
///////////////////////////////////////////////////////////////////////////////

function field_progress_bar($name, $default, $label, $ids = NULL)
{
    // An input ID is required for the label.  See why @
    // http://www.clearfoundation.com/docs/developer/framework/widgets/field_class_-_why

    $input_id = (isset($ids['input'])) ? $ids['input'] : 'clearos' . mt_rand();

    $value = set_value($name, $default);

    $html = theme_field_progress_bar($value, $label, $input_id, $ids);

    return $html;
} 

///////////////////////////////////////////////////////////////////////////////
// F O R M  H E A D E R / F O O T E R
///////////////////////////////////////////////////////////////////////////////

function form_header($title)
{
    return theme_form_header($title);
}

function form_footer()
{
    return theme_form_footer();
}

///////////////////////////////////////////////////////////////////////////////
// S U M M A R Y  T A B L E
///////////////////////////////////////////////////////////////////////////////

function summary_table($title, $anchors, $headers, $items, $legend = NULL)
{
    return theme_summary_table($title, $anchors, $headers, $items, $legend);
}

///////////////////////////////////////////////////////////////////////////////
// L I S T  T A B L E
///////////////////////////////////////////////////////////////////////////////

function list_table($title, $anchors, $headers, $items, $legend = NULL)
{
    return theme_list_table($title, $anchors, $headers, $items, $legend);
}

///////////////////////////////////////////////////////////////////////////////
// S U M M A R Y  V I E W
///////////////////////////////////////////////////////////////////////////////

function control_panel($links) {
    echo theme_control_panel($links);
}

//////////////////////////////////////////////////////////////////////////////
// D I A L O G  B O X E S
///////////////////////////////////////////////////////////////////////////////

function dialogbox_confirm_delete($message, $items, $ok_anchor, $cancel_anchor)
{
    if (! is_array($items))
        $items = array($items);

    return theme_dialogbox_confirm_delete($message, $items, $ok_anchor, $cancel_anchor);
}

function dialogbox_confirm($message, $ok_anchor, $cancel_anchor)
{
    return theme_dialogbox_confirm($message, $ok_anchor, $cancel_anchor);
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

    return "
        <div class='ui-widget'>
            <div class='ui-corner-all $class' style='margin-top: 20px; padding: 0 .7em;'>
                <span class='ui-icon $iconclass' style='float: left; margin-right: .3em; margin-top: 12px'>&nbsp; </span>$message
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
 * form_dropdown('timezone', $timezones, $timezone);
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
 */

function convert_to_hash($items) {
    $hash_array = array();

    foreach ($items as $item)
        $hash_array[$item] = $item;

    return $hash_array;
}

///////////////////////////////////////////////////////////////////////////////
// M E N U  H E L P E R
///////////////////////////////////////////////////////////////////////////////

/**
 * Menu helper.
 */

function menu($app, $tag)
{
    clearos_load_language($app);

    $translation = lang($tag);

    if (empty($translation)) {
        clearos_load_language('base');
        $translation = lang('base_other');
    }

    return $translation;
}

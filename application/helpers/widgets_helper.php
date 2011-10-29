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
// A N C H O R S
///////////////////////////////////////////////////////////////////////////////

/**
 * Custom anchor.
 *
 * @param string $url        URL of anchor
 * @param string $text       anchor text
 * @param string $importance importance of the button ('high' or 'low')
 * @param array  $options    options
 *
 * @return string HTML
 */

function anchor_custom($url, $text, $importance = 'high', $options = NULL)
{
    return theme_anchor($url, $text, $importance, 'theme-anchor-custom', $options);
}

/**
 * Dialog box anchor.
 *
 * @param string $id         HTML ID
 * @param string $text       anchor text
 * @param string $importance importance of the button ('high' or 'low')
 * @param array  $options    options
 *
 * @return string HTML
 */

function anchor_dialog($id, $text, $importance = 'high', $options = NULL)
{
    $options['id'] = $id;

    return theme_anchor_dialog('#', $text, $importance, 'theme-anchor-dialog', $options);
}

/**
 * Javascript anchor.
 *
 * @param string $id         HTML ID
 * @param string $text       anchor text
 * @param string $importance importance of the button ('high' or 'low')
 * @param array  $options    options
 *
 * @return string HTML
 */

function anchor_javascript($id, $text, $importance = 'high', $options = NULL)
{
    $options['id'] = $id;

    return theme_anchor('#', $text, $importance, 'theme-anchor-javascript', $options);
}

/**
 * Add anchor.
 *
 * @param string $url        URL of anchor
 * @param string $importance importance of the button ('high' or 'low')
 * @param array  $options    options
 *
 * @return string HTML
 */

function anchor_add($url, $importance = 'high', $options = NULL)
{
    return theme_anchor($url, lang('base_add'), $importance, 'theme-anchor-add', $options);
}

/**
 * Cancel anchor.
 *
 * @param string $url        URL of anchor
 * @param string $importance importance of the button ('high' or 'low')
 * @param array  $options    options
 *
 * @return string HTML
 */

function anchor_cancel($url, $importance = 'low', $options = NULL)
{
    return theme_anchor($url, lang('base_cancel'), $importance, 'theme-anchor-cancel', $options);
}

/**
 * Configure anchor.
 *
 * @param string $url        URL of anchor
 * @param string $importance importance of the button ('high' or 'low')
 * @param array  $options    options
 *
 * @return string HTML
 */

function anchor_configure($url, $importance = 'high', $options = NULL)
{
    return theme_anchor($url, lang('base_configure'), $importance, 'theme-anchor-configure', $options);
}

/**
 * Delete anchor.
 *
 * @param string $url        URL of anchor
 * @param string $importance importance of the button ('high' or 'low')
 * @param array  $options    options
 *
 * @return string HTML
 */

function anchor_delete($url, $importance = 'low', $options = NULL)
{
    return theme_anchor($url, lang('base_delete'), $importance, 'theme-anchor-delete', $options);
}

/**
 * Disable anchor.
 *
 * @param string $url        URL of anchor
 * @param string $importance importance of the button ('high' or 'low')
 * @param array  $options    options
 *
 * @return string HTML
 */

function anchor_disable($url, $importance = 'high', $options = NULL)
{
    return theme_anchor($url, lang('base_disable'), $importance, 'theme-anchor-disable', $options);
}

/**
 * Edit anchor.
 *
 * @param string $url        URL of anchor
 * @param string $importance importance of the button ('high' or 'low')
 * @param array  $options    options
 *
 * @return string HTML
 */

function anchor_edit($url, $importance = 'high', $options = NULL)
{
    return theme_anchor($url, lang('base_edit'), $importance, 'theme-anchor-edit', $options);
}

/**
 * Enable anchor.
 *
 * @param string $url        URL of anchor
 * @param string $importance importance of the button ('high' or 'low')
 * @param array  $options    options
 *
 * @return string HTML
 */

function anchor_enable($url, $importance = 'high', $options = NULL)
{
    return theme_anchor($url, lang('base_enable'), $importance, 'theme-anchor-enable', $options);
}

/**
 * Next anchor.
 *
 * @param string $url        URL of anchor
 * @param string $importance importance of the button ('high' or 'low')
 * @param array  $options    options
 *
 * @return string HTML
 */

function anchor_next($url, $importance = 'high', $options = NULL)
{
    return theme_anchor($url, lang('base_next'), $importance, 'theme-anchor-next', $options);
}

/**
 * Okay anchor.
 *
 * @param string $url        URL of anchor
 * @param string $importance importance of the button ('high' or 'low')
 * @param array  $options    options
 *
 * @return string HTML
 */

function anchor_ok($url, $importance = 'high', $options = NULL)
{
    return theme_anchor($url, lang('base_ok'), $importance, 'theme-anchor-ok', $options);
}

/**
 * Previous anchor.
 *
 * @param string $url        URL of anchor
 * @param string $importance importance of the button ('high' or 'low')
 * @param array  $options    options
 *
 * @return string HTML
 */

function anchor_previous($url, $importance = 'high', $options = NULL)
{
    return theme_anchor($url, lang('base_previous'), $importance, 'theme-anchor-previous', $options);
}

/**
 * View anchor.
 *
 * @param string $url        URL of anchor
 * @param string $importance importance of the button ('high' or 'low')
 * @param array  $options    options
 *
 * @return string HTML
 */

function anchor_view($url, $importance = 'high', $options = NULL)
{
    return theme_anchor($url, lang('base_view'), $importance, 'theme-anchor-view', $options);
}

///////////////////////////////////////////////////////////////////////////////
// B U T T O N S
///////////////////////////////////////////////////////////////////////////////

/**
 * Custom submit button.
 *
 * @param string $name       name of submit button
 * @param string $text       anchor text
 * @param string $importance importance of the button ('high' or 'low')
 * @param array  $options    options
 *
 * @return string HTML
 */

function form_submit_custom($name, $text, $importance = 'high', $options = NULL)
{
    return theme_form_submit($name, $text, $importance, 'theme-form-submit-custom', $options);
}

/**
 * Add submit button.
 *
 * @param string $name       name of submit button
 * @param string $importance importance of the button ('high' or 'low')
 * @param array  $options    options
 *
 * @return string HTML
 */

function form_submit_add($name, $importance = 'high', $options = NULL)
{
    return theme_form_submit($name, lang('base_add'), $importance, 'theme-form-submit-add', $options);
}

/**
 * Delete submit button.
 *
 * @param string $name       name of submit button
 * @param string $importance importance of the button ('high' or 'low')
 * @param array  $options    options
 *
 * @return string HTML
 */

function form_submit_delete($name, $importance = 'low', $options = NULL)
{
    return theme_form_submit($name, lang('base_delete'), $importance, 'theme-form-submit-delete', $options);
}

/**
 * Disable submit button.
 *
 * @param string $name       name of submit button
 * @param string $importance importance of the button ('high' or 'low')
 * @param array  $options    options
 *
 * @return string HTML
 */

function form_submit_disable($name, $importance = 'low', $options = NULL)
{
    return theme_form_submit($name, lang('base_disable'), $importance, 'theme-form-submit-disable', $options);
}

/**
 * Next submit button.
 *
 * @param string $name       name of submit button
 * @param string $importance importance of the button ('high' or 'low')
 * @param array  $options    options
 *
 * @return string HTML
 */

function form_submit_next($name, $importance = 'high', $options = NULL)
{
    return theme_form_submit($name, lang('base_next'), $importance, 'theme-form-submit-next', $options);
}

/**
 * Okay submit button.
 *
 * @param string $name       name of submit button
 * @param string $importance importance of the button ('high' or 'low')
 * @param array  $options    options
 *
 * @return string HTML
 */

function form_submit_ok($name, $importance = 'high', $options = NULL)
{
    return theme_form_submit($name, lang('base_ok'), $importance, 'theme-form-submit-ok', $options);
}

/**
 * Previous submit button.
 *
 * @param string $name       name of submit button
 * @param string $importance importance of the button ('high' or 'low')
 * @param array  $options    options
 *
 * @return string HTML
 */

function form_submit_previous($name, $importance = 'high', $options = NULL)
{
    return theme_form_submit($name, lang('base_previous'), $importance, 'theme-form-submit-previous', $options);
}

/**
 * Update submit button.
 *
 * @param string $name       name of submit button
 * @param string $importance importance of the button ('high' or 'low')
 * @param array  $options    options
 *
 * @return string HTML
 */

function form_submit_update($name, $importance = 'high', $options = NULL)
{
    return theme_form_submit($name, lang('base_update'), $importance, 'theme-form-submit-update', $options);
}

///////////////////////////////////////////////////////////////////////////////
// F I E L D S E T S
///////////////////////////////////////////////////////////////////////////////

/**
 * Field set header.
 *
 * @param string $title title
 *
 * @return string HTML
 */

function fieldset_header($title)
{
    return theme_fieldset_header($title);
}

/**
 * Field set footer.
 *
 * @return string HTML
 */

function fieldset_footer()
{
    return theme_fieldset_footer();
}

///////////////////////////////////////////////////////////////////////////////
// B U T T O N  S E T S
///////////////////////////////////////////////////////////////////////////////

/**
 * Button set.
 *
 * @param array $buttons buttons array
 * @param array $options options
 *
 * @return string HTML
 */

function button_set($buttons, $options = NULL)
{
    return theme_button_set($buttons, $options);
}

///////////////////////////////////////////////////////////////////////////////
// F I E L D  B U T T O N  S E T S
///////////////////////////////////////////////////////////////////////////////

/**
 * Button set.
 *
 * @param array $buttons buttons array
 * @param array $options options
 *
 * @return string HTML
 */

function field_button_set($buttons, $options = NULL)
{
    return theme_field_button_set($buttons, $options);
}

///////////////////////////////////////////////////////////////////////////////
// F I E L D  V I E W
///////////////////////////////////////////////////////////////////////////////
//
// For the field_X functions, an input ID is required for the label.  See why @
// http://www.clearfoundation.com/docs/developer/framework/widgets/field_class_-_why
//
///////////////////////////////////////////////////////////////////////////////

/**
 * Field view.
 *
 * @param string $label   label for text input field
 * @param string $text    text shown
 * @param string $name    name of text input element
 * @param string $value   value of text input 
 * @param array  $options options
 *
 * @return string HTML
 */

function field_view($label, $text, $name = NULL, $value = NULL, $options = NULL)
{
    $implied_id = ($name === NULL) ? 'clearos' . mt_rand() : $name;
    $input_id = (isset($options['id'])) ? $options['id'] : $implied_id;

    return theme_field_view($label, $text, $name, $value, $input_id, $options);
} 

///////////////////////////////////////////////////////////////////////////////
// F I E L D  I N P U T
///////////////////////////////////////////////////////////////////////////////

/**
 * Text input field.
 *
 * @param string $name      name of text input element
 * @param string $value     value of text input 
 * @param string $label     label for text input field
 * @param string $read_only read only flag
 * @param array  $options   options
 *
 * @return string HTML
 */

function field_input($name, $value, $label, $read_only = FALSE, $options = NULL)
{
    $input_id = (isset($options['id'])) ? $options['id'] : preg_replace('/[\[\]]/', '', $name);
    $value = ($read_only) ? $value : set_value($name, $value);
    $error = form_error($name);

    if ($read_only)
        $html = theme_field_view($label, $value, $name, $value, $input_id, $options);
    else
        $html = theme_field_input($name, $value, $label, $error, $input_id, $options);

    return $html;
} 

///////////////////////////////////////////////////////////////////////////////
// F I E L D  P A S S W O R D
///////////////////////////////////////////////////////////////////////////////

/**
 * Password input field.
 *
 * @param string $name      name of text input element
 * @param string $value     value of text input 
 * @param string $label     label for text input field
 * @param string $read_only read only flag
 * @param array  $options   options
 *
 * @return string HTML
 */

function field_password($name, $value, $label, $read_only = FALSE, $options = NULL)
{
    $input_id = (isset($options['id'])) ? $options['id'] : $name;
    $value = ($read_only) ? $value : set_value($name, $value);
    $error = form_error($name);

    if ($read_only)
        $html = theme_field_view($label, $value, $name, $value, $input_id, $options);
    else
        $html = theme_field_password($name, $value, $label, $error, $input_id, $options);

    return $html;
} 

/**
 * File input field.
 *
 * @param string $name      name of text input element
 * @param string $value     value of text input 
 * @param string $label     label for text input field
 * @param string $read_only read only flag
 * @param array  $options   options
 *
 * @return string HTML
 */

function field_file($name, $value, $label, $read_only = FALSE, $options = NULL)
{
    $input_id = (isset($options['id'])) ? $options['id'] : $name;
    $value = ($read_only) ? $value : set_value($name, $value);
    $error = form_error($name);

    if ($read_only)
        $html = theme_field_view($label, $value, $name, $value, $input_id, $options);
    else
        $html = theme_field_file($name, $value, $label, $error, $input_id, $options);

    return $html;
} 

///////////////////////////////////////////////////////////////////////////////
// F I E L D  D R O P D O W N
///////////////////////////////////////////////////////////////////////////////

/**
 * Dropdown box field for hash arrays.
 *
 * @param string $name      name of text input element
 * @param array  $values    hash list of values for dropdown
 * @param string $value     value of text input 
 * @param string $label     label for text input field
 * @param string $read_only read only flag
 * @param array  $options   options
 *
 * @return string HTML
 */

function field_dropdown($name, $values, $value, $label, $read_only = FALSE, $options = NULL)
{
    $value = ($read_only) ? $value : set_value($name, $value);
    $error = form_error($name);
    $input_id = (isset($options['id'])) ? $options['id'] : $name;

    if ($read_only)
        $html = theme_field_view($label, $values[$value], $name, $value, $input_id, $options);
    else
        $html = theme_field_dropdown($name, $value, $label, $error, $values, $input_id, $options);

    return $html;
}

///////////////////////////////////////////////////////////////////////////////
// F I E L D  S I M P L E  D R O P D O W N
///////////////////////////////////////////////////////////////////////////////

/**
 * Dropdown box field for simple arrays.
 *
 * @param string $name      name of text input element
 * @param array  $values    hash list of values for dropdown
 * @param string $value     value of text input 
 * @param string $label     label for text input field
 * @param string $read_only read only flag
 * @param array  $options   options
 *
 * @return string HTML
 */

function field_simple_dropdown($name, $values, $value, $label, $read_only = FALSE, $options = NULL)
{
    // TODO does set_value work on dropdown in CI?
    $value = ($read_only) ? $value : set_value($name, $value);
    $error = form_error($name);
    $input_id = (isset($options['id'])) ? $options['id'] : $name;

    $values = convert_to_hash($values);

    if ($read_only)
        $html = theme_field_view($label, $values[$value], $name, $value, $input_id, $options);
    else
        $html = theme_field_dropdown($name, $value, $label, $error, $values, $input_id, $options);

    return $html;
}

///////////////////////////////////////////////////////////////////////////////
// F I E L D  M U L T I S E L E C T  D R O P D O W N
///////////////////////////////////////////////////////////////////////////////

/**
 * Dropdown box field for multiselect arrays.
 *
 * @param string  $name       name of text input element
 * @param array   $values     hash list of values for dropdown
 * @param array   $selected   values to be selected
 * @param string  $label      label for text input field
 * @param boolean $use_values use displayed values as key
 * @param string  $read_only  read only flag
 * @param array   $options    options
 *
 * @return string HTML
 */

function field_multiselect_dropdown($name, $values, $selected, $label, $use_values = FALSE, $read_only = FALSE, $options = NULL)
{
    $error = form_error($name);
    $input_id = (isset($options['id'])) ? $options['id'] : $name;

    if ($use_values)
        $values = convert_to_hash($values);

    if ($read_only) {
        $intersect = array_intersect(array_keys($values), $selected);
        $value = '';
        foreach ($intersect as $arr)
            $value .= $values[$arr] . ', ';
        $html = theme_field_view($label, substr($value, 0, strlen($value) - 2), $name, $selected, $input_id, $options);
    } else {
        $html = theme_field_multiselect_dropdown($name, $selected, $label, $error, $values, $input_id, $read_only, $options);
    }

    return $html;
}

///////////////////////////////////////////////////////////////////////////////
// F I E L D  T O G G L E
///////////////////////////////////////////////////////////////////////////////

/**
 * Enable/disable toggle field.
 *
 * @param string $name      name of text input element
 * @param string $value     value of text input 
 * @param string $label     label for text input field
 * @param string $read_only read only flag
 * @param array  $options   options
 *
 * @return string HTML
 */

function field_toggle_enable_disable($name, $value, $label, $read_only = FALSE, $options = NULL)
{
    $value = ($read_only) ? $value : set_value($name, $value);
    $error = form_error($name);
    $input_id = (isset($options['id'])) ? $options['id'] : $name;

    $values = array(
        '0' => lang('base_disabled'),
        '1' => lang('base_enabled')
    );

    if ($read_only) {
        $html = theme_field_view($label, $values[$value], $name, $value, $input_id, $options);
    } else {
        $html = theme_field_toggle_enable_disable($name, $value, $label, $error, $values, $input_id, $options);
    }

    return $html;
} 

///////////////////////////////////////////////////////////////////////////////
// F I E L D  C H E C K B O X E S
///////////////////////////////////////////////////////////////////////////////

/**
 * Checkbox field.
 *
 * @param string $name      name of text input element
 * @param string $value     value of text input 
 * @param string $label     label for text input field
 * @param string $read_only read only flag
 * @param array  $options   options
 *
 * @return string HTML
 */

function field_checkbox($name, $value, $label, $read_only = FALSE, $options = NULL)
{
    $value = ($read_only) ? $value : set_value($name, $value);
    $error = form_error($name);
    $input_id = (isset($options['id'])) ? $options['id'] : $name;

    // FIXME: this needs to be improved of course
    $text = ($value) ? 'X' : '';

    if ($read_only)
        $html = theme_field_view($label, $text, $name, $value, $input_id, $options);
    else
        $html = theme_field_checkbox($name, $value, $label, $error, $input_id, $options);

    return $html;
} 

///////////////////////////////////////////////////////////////////////////////
// F I E L D  T E X T A R E A
///////////////////////////////////////////////////////////////////////////////

/**
 * Text area field.
 *
 * @param string $name      name of text area element
 * @param string $value     value of text area
 * @param string $label     label for text area field
 * @param string $read_only read only flag
 * @param array  $options   options
 *
 * @return string HTML
 */

function field_textarea($name, $value, $label, $read_only = FALSE, $options = NULL)
{
    $input_id = (isset($options['id'])) ? $options['id'] : preg_replace('/[\[\]]/', '', $name);
    $value = ($read_only) ? $value : set_value($name, $value);
    $error = form_error($name);

    if ($read_only)
        $html = theme_field_view($label, $value, $name, $value, $input_id, $options);
    else
        $html = theme_field_textarea($name, $value, $label, $error, $input_id, $options);

    return $html;
} 

///////////////////////////////////////////////////////////////////////////////
// R A D I O  S E T S
///////////////////////////////////////////////////////////////////////////////

function form_radio_set_open($class, $orientation, $options = NULL)
{
    return "<div class='$class'>\n";
}

function form_radio_set_item($id, $name, $label, $checked = FALSE)
{
    return "<input type='radio' id='$id' name='$name' /><label for='$id'>$label</label>\n";
}

function form_radio_set_close()
{
    return "</div>\n";
}

///////////////////////////////////////////////////////////////////////////////
// P R O G R E S S  B A R S
///////////////////////////////////////////////////////////////////////////////

/**
 * Display a progress bar as part of a form field.
 *
 * @param string $label   form field label
 * @param string $id      HTML ID
 * @param array  $options options
 *
 * @return string HTML output
 */

function field_progress_bar($label, $id, $options)
{
    return theme_field_progress_bar($label, $id, $options);
}

/**
 * Display a progress bar as standalone entity.
 *
 * @param string $id      HTML ID
 * @param array  $options options
 *
 * @return string HTML output
 */

function progress_bar($id, $options)
{
    return theme_progress_bar($id, $options);
} 

///////////////////////////////////////////////////////////////////////////////
// F O R M  H E A D E R / F O O T E R
///////////////////////////////////////////////////////////////////////////////

/**
 * Form header.
 *
 * @param string $title   form title
 * @param array  $options options
 *
 * @return string HTML
 */

function form_header($title, $options = NULL)
{
    return theme_form_header($title, $options);
}

/**
 * Form footer.
 *
 * @param array $options options
 *
 * @return string HTML
 */

function form_footer($options = NULL)
{
    return theme_form_footer($options);
}

///////////////////////////////////////////////////////////////////////////////
// T A B  V I E W
///////////////////////////////////////////////////////////////////////////////

/**
 * Provide tabular summary of content.
 *
 * @param array  $content content
 *
 * @return string HTML
 */

function tab($content)
{
    return theme_tab($content);
}

///////////////////////////////////////////////////////////////////////////////
// L O A D I N G  I C O N
///////////////////////////////////////////////////////////////////////////////

/**
 * Provide loading graphic for AJAX operations.
 *
 * @param string $size size
 *
 * @return string HTML
 */

function loading($size = 'normal', $text = '', $options = NULL)
{
    return theme_loading($size, $text, $options);
}

///////////////////////////////////////////////////////////////////////////////
// A C T I O N  T A B L E
///////////////////////////////////////////////////////////////////////////////

/**
 * Summary table.
 *
 * @param string $title   table title
 * @param array  $items   items
 * @param array  $options options
 *
 * @return string HTML
 */

function action_table($title, $items, $options = NULL)
{
    return theme_action_table($title, $items, $options);
}

///////////////////////////////////////////////////////////////////////////////
// S U M M A R Y  T A B L E
///////////////////////////////////////////////////////////////////////////////

/**
 * Summary table.
 *
 * @param string $title   table title
 * @param array  $anchors list anchors
 * @param array  $headers headers
 * @param array  $items   items
 * @param array  $options options
 *
 * @return string HTML
 */

function summary_table($title, $anchors, $headers, $items, $options = NULL)
{
    return theme_summary_table($title, $anchors, $headers, $items, $options);
}

///////////////////////////////////////////////////////////////////////////////
// L I S T  T A B L E
///////////////////////////////////////////////////////////////////////////////

/**
 * List table.
 *
 * @param string $title   table title
 * @param array  $anchors list anchors
 * @param array  $headers headers
 * @param array  $items   items
 * @param array  $options options
 *
 * @return string HTML
 */

function list_table($title, $anchors, $headers, $items, $options = NULL)
{
    return theme_list_table($title, $anchors, $headers, $items, $options);
}

//////////////////////////////////////////////////////////////////////////////
// D I A L O G  B O X E S
///////////////////////////////////////////////////////////////////////////////

/**
 * Confirm delete dialog box.
 *
 * @param string $message delete message
 * @param array  $items   list of items to delete
 * @param string $confirm_anchor URL  $headers headers
 * @param array  $items   items
 * @param array  $options options
 *
 * @return string HTML
 */

function dialogbox_confirm_delete($message, $items, $confirm_anchor, $cancel_anchor)
{
    if (! is_array($items))
        $items = array($items);

    return theme_dialogbox_confirm_delete($message, $items, $confirm_anchor, $cancel_anchor);
}

function dialogbox_confirm($message, $confirm_anchor, $cancel_anchor)
{
    return theme_dialogbox_confirm($message, $confirm_anchor, $cancel_anchor);
}

///////////////////////////////////////////////////////////////////////////////
// I N F O  B O X E S
///////////////////////////////////////////////////////////////////////////////

/**
 * Critical infobox.
 *
 * @param string $title   table title
 * @param string $message message
 * @param array  $options options
 *
 * @return string HTML
 */

function infobox_critical($title, $message, $options = NULL)
{
    return theme_infobox('critical', $title, $message, $options);
}

/**
 * Warning infobox.
 *
 * @param string $title   table title
 * @param string $message message
 * @param array  $options options
 *
 * @return string HTML
 */

function infobox_warning($title, $message, $options = NULL)
{
    return theme_infobox('warning', $title, $message, $options);
}

/**
 * Highlight infobox.
 *
 * @param string $title   table title
 * @param string $message message
 * @param array  $options options
 *
 * @return string HTML
 */

function infobox_highlight($title, $message, $options = NULL)
{
    return theme_infobox('highlight', $title, $message, $options);
}

///////////////////////////////////////////////////////////////////////////////
// S U M M A R Y  V I E W
///////////////////////////////////////////////////////////////////////////////

function control_panel($links) {
    echo theme_control_panel($links);
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

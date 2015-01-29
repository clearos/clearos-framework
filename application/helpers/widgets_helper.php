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
 * @copyright  2011-2014 ClearFoundation
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
// A N C H O R S - S T A N D A R D
///////////////////////////////////////////////////////////////////////////////

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
 * Select anchor.
 *
 * @param string $url        URL of anchor
 * @param string $importance importance of the button ('high' or 'low')
 * @param array  $options    options
 *
 * @return string HTML
 */

function anchor_select($url, $importance = 'low', $options = NULL)
{
    return theme_anchor($url, lang('base_select'), $importance, 'theme-anchor-select', $options);
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
// A N C H O R S - S P E C I A L
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
 * Multi-select anchor.
 *
 * @param string $urls       URLs of anchor
 * @param string $text       anchor text
 * @param string $importance importance of the button ('high' or 'low')
 * @param array  $options    options
 *
 * @return string HTML
 */

function anchor_multi($urls, $text, $importance = 'high', $options = NULL)
{
    return theme_anchor($urls, $text, $importance, 'theme-anchor-multi', $options);
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
 * Select button.
 *
 * @param string $name       name of select button
 * @param string $importance importance of the button ('high' or 'low')
 * @param array  $options    options
 *
 * @return string HTML
 */

function form_submit_select($name, $importance = 'high', $options = NULL)
{
    return theme_form_submit($name, lang('base_select'), $importance, 'theme-form-submit-select', $options);
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
 * @param array  $options    options
 *
 * @return string HTML
 */

function fieldset_header($title, $options = NULL)
{
    return theme_fieldset_header($title, $options);
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

///////////////////////////////////////////////////////////////////////////////
// F I E L D  B A N N E R
///////////////////////////////////////////////////////////////////////////////

/**
 * Displays a single block of text instead of showing a field/value pair.
 *
 * @param string $text    text shown
 * @param array  $options options
 *
 * @return string HTML
 */

function field_banner($text, $options = NULL)
{
    return theme_field_banner($text, $options);
}

///////////////////////////////////////////////////////////////////////////////
// F I E L D  V I E W
///////////////////////////////////////////////////////////////////////////////
// FIXME: It looks like field_view and field_info do the same thing.  Merge.

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
    $implied_id = ($name === NULL) ? 'clearos' . mt_rand() : convert_to_id($name);
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
    $input_id = (isset($options['id'])) ? $options['id'] : convert_to_id($name);
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
    $input_id = (isset($options['id'])) ? $options['id'] : convert_to_id($name);
    $value = ($read_only) ? $value : set_value($name, $value);
    $error = form_error($name);

    if ($read_only)
        $html = theme_field_view($label, $value, $name, $value, $input_id, $options);
    else
        $html = theme_field_password($name, $value, $label, $error, $input_id, $options);

    return $html;
} 

///////////////////////////////////////////////////////////////////////////////
// F I E L D  C O L O U R
///////////////////////////////////////////////////////////////////////////////

/**
 * Colour input field.
 *
 * @param string $name      name of text input element
 * @param string $value     value of text input 
 * @param string $label     label for text input field
 * @param string $read_only read only flag
 * @param array  $options   options
 *
 * @return string HTML
 */

function field_color($name, $value, $label, $read_only = FALSE, $options = NULL)
{
    $input_id = (isset($options['id'])) ? $options['id'] : convert_to_id($name);
    $value = ($read_only) ? $value : set_value($name, $value);
    $error = form_error($name);
    $options['color-picker'] = TRUE;

    if ($read_only)
        $html = theme_field_view($label, $value, $name, $value, $input_id, $options);
    else
        $html = theme_field_color($name, $value, $label, $error, $input_id, $options);

    return $html;
} 

///////////////////////////////////////////////////////////////////////////////
// F I E L D  F I L E  I N P U T
///////////////////////////////////////////////////////////////////////////////

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
    $input_id = (isset($options['id'])) ? $options['id'] : convert_to_id($name);
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
    $input_id = (isset($options['id'])) ? $options['id'] : convert_to_id($name);

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
    $input_id = (isset($options['id'])) ? $options['id'] : convert_to_id($name);

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
    $input_id = (isset($options['id'])) ? $options['id'] : convert_to_id($name);

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
    if (isset($value) || $value == '' || $value == NULL)
        $value = 0;
    $error = form_error($name);
    $input_id = (isset($options['id'])) ? $options['id'] : convert_to_id($name);

    $values = array(
        0 => lang('base_disabled'),
        1 => lang('base_enabled')
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
    $input_id = (isset($options['id'])) ? $options['id'] : convert_to_id($name);

    if ($read_only)
        $html = theme_field_view($label, (bool)$value, $name, $value, $input_id, $options);
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
    $input_id = (isset($options['id'])) ? $options['id'] : convert_to_id($name);
    $value = ($read_only) ? $value : set_value($name, $value);
    $error = form_error($name);

    if ($read_only)
        $html = theme_field_view($label, $value, $name, $value, $input_id, $options);
    else
        $html = theme_field_textarea($name, $value, $label, $error, $input_id, $options);

    return $html;
} 

///////////////////////////////////////////////////////////////////////////////
// F I E L D  I N F O
///////////////////////////////////////////////////////////////////////////////

/**
 * Display some information.
 *
 * @param string $id      HTML ID
 * @param string $label   label
 * @param string $text    text
 * @param array  $options options
 *
 * @return string HTML output
 */

function field_info($id, $label, $text, $options = NULL)
{
    return theme_field_info($id, $label, $text, $options);
}

///////////////////////////////////////////////////////////////////////////////
// R A D I O  S E T S
///////////////////////////////////////////////////////////////////////////////

/**
 * Radio set.
 *
 * @param array  $radios   radio array
 * @param string $input_id input ID
 * @param array  $options  options
 *
 * @return string HTML
 */

function radio_set($radios, $input_id, $options = NULL)
{
    return theme_radio_set($radios, $input_id, $options);
}

/**
 * Radio set.
 *
 * @param string $title    title
 * @param array  $radios   radio array
 * @param string $input_id input ID
 * @param array  $options  options
 *
 * @return string HTML
 */

function field_radio_set($title, $radios, $input_id, $options = NULL)
{
    return theme_field_radio_set($title, $radios, $input_id, $options);
}

/**
 * Radio set item.
 *
 * @param string $name      name of text input element
 * @param string $group     button group
 * @param string $label     label for text input field
 * @param string $checked   checked flag
 * @param array  $options   options
 *
 * @return string HTML
 */

function radio_set_item($name, $group, $label, $checked = FALSE, $options = NULL) 
{
    $input_id = (isset($options['id'])) ? $options['id'] : convert_to_id($name);

    $html = theme_radio_set_item($name, $group, $label, $checked, $input_id, $options);

    return $html;
}

/**
 * Radio set item.
 *
 * @param string $name      name of text input element
 * @param string $group     button group
 * @param string $label     label for text input field
 * @param string $checked   checked flag
 * @param string $read_only read only flag
 * @param array  $options   options
 *
 * @return string HTML
 */

function field_radio_set_item($name, $group, $label, $checked = FALSE, $read_only = FALSE, $options = NULL) 
{
    $checked = ($read_only) ? $checked : set_value($name, $checked);
    $error = form_error($name);
    $input_id = (isset($options['id'])) ? $options['id'] : convert_to_id($name);

    // TODO: read only mode has not been used/tested
    if ($read_only)
        $html = theme_field_view($label, (bool)$checked, $name, $checked, $input_id, $options);
    else
        $html = theme_field_radio_set_item($name, $group, $label, $checked, $error, $input_id, $options);

    return $html;
}

///////////////////////////////////////////////////////////////////////////////
// S L I D E R S
///////////////////////////////////////////////////////////////////////////////

/**
 * Display a slider as part of a form field.
 *
 * @param string $label   form field label
 * @param string $id      HTML ID
 * @param int    $value   value
 * @param int    $min     minimum
 * @param int    $max     maximum
 * @param int    $step    step
 * @param array  $options options
 *
 * @return string HTML output
 */

function field_slider($label, $id, $value, $min, $max, $step, $options)
{
    return theme_field_slider($label, $id, $value, $min, $max, $step, $options);
}

/**
 * Display slider set.
 *
 * @param array  $sliders  list of sliders in HTML format
 * @param string $input_id input ID
 * @param array  $options  options
 *
 * @return string HTML for field slider set
 */

function slider_set($sliders, $input_id, $options = NULL)
{
    return theme_slider_set($sliders, $input_id, $options);
}

/**
 * Display slider set.
 *
 * @param array  $sliders  list of sliders in HTML format
 * @param string $input_id input ID
 * @param array  $options  options
 *
 * @return string HTML for field slider set
 */

function field_slider_set($sliders, $input_id, $options = NULL)
{
    return theme_field_slider_set($sliders, $input_id, $options);
}

/**
 * Return slider set item.
 *
 * @param string $input_id    input ID
 * @param int    $value       value
 * @param int    $min         minimum
 * @param int    $max         maximum
 * @param int    $step        step
 * @param string $orientation orientation
 * @param array  $options     options
 *
 */

function slider_set_item($input_id, $value, $min, $max, $step, $orientation, $options)
{
    return theme_slider_set_item($input_id, $value, $min, $max, $step, $orientation, $options);
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
// L O G I N   P A G E / F O R M
///////////////////////////////////////////////////////////////////////////////

/**
 * Display a login page.
 *
 * @param string $redirect  redirect URL
 * @param string $languages language options
 * @param string $lang      language pref
 * @param string $errmsg    failed login message
 * @param array  $options options
 *
 * @return string HTML output
 */

function login_form($redirect, $languages, $lang = 'en_US', $errmsg = NULL, $options = NULL)
{
    return theme_login_form($redirect, $languages, $lang, $errmsg, $options);
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
 * Form banner.
 *
 * @param string $html    html payload
 * @param array  $options options
 *
 * @return string HTML
 */

function form_banner($html, $options = NULL)
{
    return theme_form_banner($html, $options);
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
// S I D E B A R  H E A D E R / F O O T E R
///////////////////////////////////////////////////////////////////////////////

/**
 * Sidebar header.
 *
 * @param string $title   sidebar title
 * @param array  $options options
 *
 * @return string HTML
 */

function sidebar_header($title, $options = NULL)
{
    return theme_sidebar_header($title, $options);
}

/**
 * Sidebar banner.
 *
 * @param string $html    html payload
 * @param array  $options options
 *
 * @return string HTML
 */

function sidebar_banner($banner, $options = NULL)
{
    return theme_sidebar_banner($banner, $options);
}

/**
 * Sidebar key value.
 *
 * @param string $html    html payload
 * @param array  $options options
 *
 * @return string HTML
 */

function sidebar_value($value, $label, $options = NULL)
{
    $base_id = (isset($options['id'])) ? $options['id'] : '';

    return theme_sidebar_value($value, $label, $base_id, $options);
}

/**
 * Sidebar text.
 *
 * @param string $html    html payload
 * @param array  $options options
 *
 * @return string HTML
 */

function sidebar_text($text, $options = NULL)
{
    return theme_sidebar_text($text, $options);
}

/**
 * Sidebar footer.
 *
 * @param array $options options
 *
 * @return string HTML
 */

function sidebar_footer($options = NULL)
{
    return theme_sidebar_footer($options);
}

///////////////////////////////////////////////////////////////////////////////
// C H A R T  W I D G E T
///////////////////////////////////////////////////////////////////////////////

/**
 * Chart widget.
 *
 * @param string $title   form title
 * @param string $payload payload
 * @param array  $options options
 *
 * @return string HTML
 */

function chart_container($title, $payload, $options = NULL)
{
    return theme_chart_container($title, $payload, $options);
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

function dialogbox_confirm_delete($message, $items, $confirm_anchor, $cancel_anchor, $options)
{
    if (! is_array($items))
        $items = array($items);

    return theme_dialogbox_confirm_delete($message, $items, $confirm_anchor, $cancel_anchor, $options);
}

/**
 * Confirm delete dialog box.
 *
 * @param string $message        message
 * @param string $confirm_anchor URL
 * @param string $cancel_anchor  URL
 * @param array  $options        options
 *
 * @return string HTML
 */

function dialogbox_confirm($message, $confirm_anchor, $cancel_anchor, $options = NULL)
{
    return theme_dialogbox_confirm($message, $confirm_anchor, $cancel_anchor, $options);
}

/**
 * Modal info box.
 *
 * @param string $id      DOM id
 * @param string $title   title
 * @param string $message message
 * @param array  $options options
 *
 * @return string HTML
 */

function modal_info($id, $title, $message, $options)
{
    return theme_modal_info($id, $title, $message, $options);
}

/**
 * Modal confirmation box.
 *
 * @param string $title   title
 * @param string $message message
 * @param string $confirm confirm URL if using anchor (not used if using forms/form_id)
 * @param array  $trigger type (id or class) and selector
 * @param array  $form_id form ID (used if you want to submit a form on confirmation)
 * @param array  $id      modal div ID
 * @param array  $options options
 *
 * @return string HTML
 */

function modal_confirm($title, $message, $confirm, $trigger, $form_id, $id, $options)
{
    return theme_modal_confirm($title, $message, $confirm, $trigger, $form_id, $id, $options);
}

/**
 * Modal input box.
 *
 * @param string $title    title
 * @param string $message  message
 * @param array  $trigger  type (id or class) and selector
 * @param array  $input_id the ID where the input should get copied to
 * @param array  $id       modal div ID
 * @param array  $options  options
 *
 * @return string HTML
 */

function modal_input($title, $message, $trigger, $input_id, $id, $options)
{
    return theme_modal_input($title, $message, $trigger, $input_id, $id, $options);
}

///////////////////////////////////////////////////////////////////////////////
// I N F O  B O X E S
///////////////////////////////////////////////////////////////////////////////

/**
 * Infobox with anchor to 'follow me' link.
 *
 * @param string $title     table title
 * @param string $message   message
 * @param string $url       url
 * @param string $link_text link text
 * @param array  $options options
 *
 * @return string HTML
 */

function infobox_and_redirect($title, $message, $url, $link_text, $options = NULL)
{
    return theme_infobox_and_redirect($title, $message, $url, $link_text, $options);
}

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

/**
 * Default infobox.
 *
 * @param string $title   table title
 * @param string $message message
 * @param array  $options options
 *
 * @return string HTML
 */

function infobox_info($title, $message, $options = NULL)
{
    return theme_infobox('info', $title, $message, $options);
}

/**
 * Open a box.
 *
 * @param string $title   title
 * @param array  $options options
 *
 * @return string HTML
 */

function box_open($title = NULL, $options = NULL)
{
    return theme_box_open($title, $options);
}

/**
 * Open box content, provide content and close container.
 *
 * @param string $content content
 * @param array  $options options
 *
 * @return string HTML
 */

function box_content($content, $options = NULL)
{
    return theme_box_content($content, $options);
}

/**
 * Open box content.
 *
 * @param array  $options options
 *
 * @return string HTML
 */

function box_content_open($options = NULL)
{
    return theme_box_content_open($options);
}

/**
 * Close box box content.
 *
 * @param array  $options options
 *
 * @return string HTML
 */

function box_content_close()
{
    return theme_box_content_close();
}

/**
 * Close box.
 *
 * @return string HTML
 */

function box_close()
{
    return theme_box_close();
}

/**
 * Box footer.
 *
 * @param string $id      DOM ID
 * @param string $content content
 * @param array  $options options
 *
 * @return string HTML
 */

function box_footer($id = NULL, $content = '', $options)
{
    if ($id == NULL)
        $id = 'bf-' . rand(0, 100);

    return theme_box_footer($id, $content, $options);
}

/**
 * Open a row.
 *
 * @param array  $options options
 *
 * @return string HTML
 */

function row_open($options = NULL)
{
    return theme_row_open($options);
}

/**
 * End a row.
 *
 * @param array  $options options
 *
 * @return string HTML
 */

function row_close($options = NULL)
{
    return theme_row_close($options);
}

/**
 * Open a column.
 *
 * @param array  $options options
 *
 * @return string HTML
 */

function column_open($desktop, $tablet = NULL, $phone = NULL, $options = NULL)
{
    return theme_column_open($desktop, $tablet, $phone, $options);
}

/**
 * End a column.
 *
 * @param array  $options options
 *
 * @return string HTML
 */

function column_close($options = NULL)
{
    return theme_column_close($options);
}

///////////////////////////////////////////////////////////////////////////////
// S U M M A R Y  V I E W
///////////////////////////////////////////////////////////////////////////////

function control_panel($links) {
    echo theme_control_panel($links);
}

///////////////////////////////////////////////////////////////////////////////
// I M A G E S
///////////////////////////////////////////////////////////////////////////////

/**
 * Get Image.
 *
 * @param string $name    name of image
 * @param array  $options options
 *
 * @return string HTML
 */

function image($name, $options = NULL)
{
    $parts = explode('/', uri_string());
    return theme_image($name, $parts[0], $options);
}

/**
 * App Logo.
 *
 * @param string $basename app base name
 * @param array  $options options
 *
 * @return string HTML
 */

function app_logo($basename = NULL, $options = NULL)
{
    return theme_app_logo($basename, $options);
}

/**
 * Returns a screenshot display list.
 *
 * @param string $id      id
 * @param array  $images  array of metadata containing screenshot info
 * @param array  $options options
 *
 * @return string HTML
 */

function screenshot_set($id, $images = NULL, $options = NULL)
{
    return theme_screenshot_set($id, $images, $options);
}

///////////////////////////////////////////////////////////////////////////////
// M A R K E T P L A C E
///////////////////////////////////////////////////////////////////////////////

/**
 * Returns marketplace filter options.
 *
 * @param string $name     name
 * @param array  $values   values
 * @param string $selected selected value
 * @param array  $options  options
 *
 * @return string HTML
 */

function marketplace_filter($name, $values, $selected = 'all', $options = NULL)
{
    return theme_marketplace_filter($name, $values, $selected, $options);
}

/**
 * Returns paginate HTML markup.
 *
 * @param array $url     base URL
 * @param int   $pages   number of pages
 * @param int   $active  active page
 * @param int   $max     maximum paginations to link to
 * @param array $options options
 *
 * @return string HTML
 */

function paginate($url, $pages = 0, $active = 0, $max = 5, $options = NULL)
{
    return theme_paginate($url, $pages, $active, $max, $options);
}

/**
 * Returns marketplace search.
 *
 * @param string $search_string search string
 *
 * @return string HTML
 */

function marketplace_search($search_string = NULL)
{
    return theme_marketplace_search($search_string);
}

/**
 * Returns marketplace HTML for developer field.
 *
 * @param string $id      id
 * @param string $field   human readable field name
 * @param array  $options options
 *
 * @return string HTML
 */

function marketplace_developer_field($id, $field, $options)
{
    return theme_marketplace_developer_field($id, $field, $options = NULL);
}

/**
 * Returns marketplace HTML for review .
 *
 * @param string $basename basename
 * @param string $pseudonym, pseudonymn
 * @param array  $options options
 *
 * @return string HTML
 */

function marketplace_review($basename, $pseudonym, $options)
{
    return theme_marketplace_review($basename, $pseudonym, $options = NULL);
}

/**
 * Returns marketplace HTML for layout of apps.
 *
 * @return string HTML
 */

function marketplace_layout()
{
    return theme_marketplace_layout();
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

/**
 * Converts an HTML name to a valid ID.
 *
 * By default, the HTML ID will be automatically set tto the HTML name if
 * an ID is not specified.  e.g.: <input name='ip_address' value='1.2.3.4'> 
 * will be changed to <input name='ip_address' id='ip_address' ...>
 * In PHP, it's common to use arrays for an input name:
 *
 * <input name='user_info[address][street]' ...>
 *
 * The [] characters are not valid, so these are converted to periods:
 *
 * <input name='user_info.addres.street' ...>
 */

function convert_to_id($name) {
    $id = $name;

    $id = preg_replace('/\[\]/', '', $id);
    $id = preg_replace('/\]\[/', '.', $id);
    $id = preg_replace('/\[/', '.', $id);
    $id = preg_replace('/\]/', '', $id);

    return $id;
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

///////////////////////////////////////////////////////////////////////////////
// D E V E L O P E R  H E L P E R
///////////////////////////////////////////////////////////////////////////////

/**
 * Debug/echo array.
 */

function devel_print_r($obj)
{
    //  echo "<pre style='text-align: left; clear: both; position: absolute; background-color: white; width: 100%;z-index: 9999;'>";
    //  print_r($obj);
    //  echo "</pre>";
    echo "<script type='text/javascript'>\n";
    echo "  console.log(" . json_encode($obj) . ");\n";
    echo "</script>\n";
}

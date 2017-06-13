<?php

function form_open($action = '', $attributes = '', $hidden = array(), $options = array())
{
    $CI =& get_instance();

    if (empty($attributes) || !array_key_exists('method', $attributes))
        $attributes['method'] = 'post';

    if ($attributes == '')
    {
        $attributes = 'method="post"';
    }

    // If an action is not a full URL then turn it into one
    if ($action && strpos($action, '://') === FALSE)
    {
        $action = $CI->config->site_url($action);
    }

    // If no action is provided then set to the current url
    $action OR $action = $CI->config->site_url($CI->uri->uri_string());

    $form = '<form action="'.$action.'"';

    // Add hook to ClearOS theme engine
    if (function_exists('theme_form_classes')) {
        $theme_classes = implode(' ', theme_form_classes($options));
        $form .= ' class="' . $theme_classes . '" ';
    }

    $form .= _attributes_to_string($attributes, TRUE);

    $form .= '>';

    // Add CSRF field if enabled, but leave it out for GET requests and requests to external websites   
    if ($CI->config->item('csrf_protection') === TRUE AND ! (strpos($action, $CI->config->base_url()) === FALSE OR strpos($form, 'method="get"')))
    {
        $hidden[$CI->security->get_csrf_token_name()] = $CI->security->get_csrf_hash();
    }

    if (is_array($hidden) AND count($hidden) > 0)
    {
        $form .= sprintf("<div style=\"display:none\">%s</div>", form_hidden($hidden));
    }

    return $form;
}

function form_fieldset($legend_text = '', $attributes = array())
{
    // ClearFoundation - add default classes
    $fieldset = "<fieldset class='theme-fieldset'";

    $fieldset .= _attributes_to_string($attributes, FALSE);

    $fieldset .= ">\n";

    if ($legend_text != '')
    {
        $fieldset .= "<legend class='theme-fieldset-legend'>$legend_text</legend>\n";
    }

    return $fieldset;
}


function form_close()
{
    return '</form>';
}


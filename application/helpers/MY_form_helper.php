<?php

function form_open($action = '', $attributes = '', $hidden = array())
{
    $CI =& get_instance();

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

    // ClearFoundation
    // - added a wrapper around form
    // - added default form class
    // - added a newline 
    $form = "<div class='theme-form-container'>\n";
    $form .= '<form class="theme-form" action="'.$action.'"';

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


function form_close($extra = '')
{
    // ClearFoundation add a wrapper around form
    return "</form>".$extra."</div>\n";
}


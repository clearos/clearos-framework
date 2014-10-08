<?php

/**
 * Javascript helper for site-wide access.
 *
 * @category   Apps
 * @package    framework
 * @subpackage Javascript
 * @author     ClearCenter <developer@clearcenter.com>
 * @copyright  2011 ClearCenter
 * @license    http://www.clearcenter.com/Company/terms.html ClearSDN license
 * @link       http://www.clearcenter.com/support/documentation/clearos/system_monitor/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// B O O T S T R A P
///////////////////////////////////////////////////////////////////////////////

$bootstrap = getenv('CLEAROS_BOOTSTRAP') ? getenv('CLEAROS_BOOTSTRAP') : '/usr/clearos/framework/shared';
require_once $bootstrap . '/bootstrap.php';

clearos_load_language('base');
clearos_load_language('marketplace');

header('Content-Type: application/x-javascript');

echo "
// Globally defined object for changing the way the SDN auth dialog handles
var auth_options = new Object();
var sdn_org = '';
var internet_connection = false;
var UNIT = [];
UNIT[0] = '';
UNIT[9] = '';
UNIT[100] = '" . lang('marketplace_monthly') . "';
UNIT[1000] = '" . lang('marketplace_1_year') . "';
UNIT[2000] = '" . lang('marketplace_2_year') . "';
UNIT[3000] = '" . lang('marketplace_3_year') . "';
var lang = new Object();
var lang_yes = '" . lang("base_yes") . "';
var lang_no = '" . lang("base_no") . "';
var review_by = '" . lang('marketplace_by') . "';
var lang_cancel = '" . lang("base_cancel") . "';
var lang_close = '" . lang("base_close") . "';
var lang_authenticate = '" . lang("base_authenticate") . "';
var lang_sdn_authentication_required = '" . lang("base_sdn_authentication_required") . "';
var lang_sdn_authentication_required_help = '" . lang("base_sdn_authentication_required_help") . "';
var lang_username = '" . lang("base_username") . "';
var lang_password = '" . lang("base_password") . "';
var lang_forgot_password = '" . lang("base_forgot_password") . "';
var lang_sdn_email = '" . lang("base_sdn_email") . "';
var lang_sdn_password_invalid = '" . lang("base_sdn_password_invalid") . "';
var lang_login = '" . lang("base_login") . "';
var lang_success = '" . lang("base_success") . "';
var lang_reset_password_and_send = '" . lang("base_reset_password_and_send") . "';
var lang_sdn_email_invalid = '" . lang("base_sdn_email_invalid") . "';
var lang_sdn_email_mismatch = '" . lang("base_sdn_email_mismatch") . "';
var lang_sdn_password_reset = '" . lang("base_sdn_password_reset") . "';
var lang_status = '" . lang('base_status') . "';
var lang_uninstall = '" . lang('base_uninstall') . "';
var lang_configure = '" . lang('base_configure') . "';
var lang_internet_down = '" . lang('base_check_internet_connection') . "';
var lang_installed = '" . lang('base_installed') . "';
var lang_marketplace_connection_failure = '" . lang('marketplace_connection_failure') . "';
var lang_marketplace_redemption = '" . lang('marketplace_redemption') . "';
var lang_more_info = '" . lang('marketplace_more_info') . "';
var lang_marketplace_expired_no_subscription = '" . lang('marketplace_expired_no_subsription') . "';
var lang_marketplace_billing_cycle_monthly = '" . lang('marketplace_billing_cycle_monthly') . "';
var lang_marketplace_billing_cycle_yearly = '" . lang('marketplace_billing_cycle_yearly') . "';
var lang_marketplace_billing_cycle_2_years = '" . lang('marketplace_billing_cycle_2_years') . "';
var lang_marketplace_billing_cycle_3_years = '" . lang('marketplace_billing_cycle_3_years') . "';
var lang_marketplace_billing_cycle = '" . lang('marketplace_billing_cycle') . "';
var lang_marketplace_renewal_date = '" . lang('marketplace_renewal_date') . "';
var lang_marketplace_upgrade = '" . lang('marketplace_upgrade') . "';
var lang_marketplace_free = '" . lang('marketplace_free') . "';
var lang_marketplace_select_for_install = '" . lang('marketplace_select_for_install') . "';
var lang_marketplace_remove = '" . lang('marketplace_remove') . "';
var lang_marketplace_sidebar_recommended_apps = '" . lang('marketplace_sidebar_recommended_apps') . "';
var lang_marketplace_recommended_apps = '" . lang('marketplace_recommended_apps') . "';
var lang_marketplace_evaluation = '" . lang('marketplace_evaluation') . "';
var lang_marketplace_trial_ends = '" . lang('marketplace_trial_ends') . "';
var lang_marketplace_search_marketplace = '" . lang('marketplace_search_marketplace') . "';
var lang_marketplace_search_no_results = '" . lang('marketplace_search_no_results') . "';
var lang_marketplace_eval_limitations = '" . lang('marketplace_eval_limitations') . "';
var lang_marketplace_support_1_title = '" . lang('marketplace_support_1_title') . "';
var lang_marketplace_support_2_title = '" . lang('marketplace_support_2_title') . "';
var lang_marketplace_support_4_title = '" . lang('marketplace_support_4_title') . "';
var lang_marketplace_support_8_title = '" . lang('marketplace_support_8_title') . "';
var lang_marketplace_support_16_title = '" . lang('marketplace_support_16_title') . "';
var lang_marketplace_support_1_description = '" . lang('marketplace_support_1_description') . "';
var lang_marketplace_support_2_description = '" . lang('marketplace_support_2_description') . "';
var lang_marketplace_support_4_description = '" . lang('marketplace_support_4_description') . "';
var lang_marketplace_support_8_description = '" . lang('marketplace_support_8_description') . "';
var lang_marketplace_support_16_description = '" . lang('marketplace_support_16_description') . "';
var lang_marketplace_support_policy = '" . lang('marketplace_support_policy') . "';
var lang_marketplace_support_legend = '" . lang('marketplace_support_legend') . "';
var lang_marketplace_learn_more = '" . lang('marketplace_learn_more') . "';
var lang_marketplace_sdn_account_setup_help_1 = '" . lang("marketplace_sdn_account_setup_help_1") . "';
var lang_marketplace_sdn_account_setup_help_2 = '" . lang("marketplace_sdn_account_setup_help_2") . "';
var lang_marketplace_sdn_account_setup = '" . lang('marketplace_sdn_account_setup') . "';
var lang_marketplace_setup_payment_on_clear = '" . lang('marketplace_setup_payment_on_clear') . "';
var lang_warning = '" . lang("base_warning") . "';

// Main
//-----

$.ajaxSetup({ cache: false });

my_location = get_location_info();

$(document).ready(function() {
    theme_clearos_on_page_ready(my_location);
});

// Functions
//----------

function clearos_is_authenticated() {
    data_payload = 'ci_csrf_token=' + $.cookie('ci_csrf_token');
    if ($('#sdn_username').val() != undefined)
        data_payload += '&username=' + $('#sdn_username').val();
    $('#sdn-login-dialog-message-bar').html('');
    if (auth_options.action_type == 'login') {
        if ($('#sdn_password').val() == '') {
            $('#sdn-login-dialog-message-bar').html(theme_clearos_info_box('warning', lang_warning, lang_sdn_password_invalid));
            $('#sdn-login-dialog-message-bar').show(200);
            $('.autofocus').focus();
            return;
        } else {
            data_payload += '&password=' + $('#sdn_password').val();
        }
    } else if (auth_options.action_type == 'lost_password') {
        if ($('#sdn_email').val() == '') {
            $('#sdn-login-dialog-message-bar').html(theme_clearos_info_box('warning', lang_warning, lang_sdn_email_invalid));
            $('#sdn-login-dialog-message-bar').show(200);
            $('.autofocus').focus();
            return;
        } else {
            data_payload += '&email=' + $('#sdn_email').val();
        }
    }

    $.ajax({
        type: 'POST',
        dataType: 'json',
        data: data_payload,
        url: '/app/marketplace/ajax/is_authenticated',
        success: function(data) {
            if (data.code == 0 && data.authorized) {
                // Might have pages where account is displayed (eg. Marketplace)
                $('#display_sdn_username').html(data.sdn_username);
                // Only case where authorized is true.
                $('#sdn-login-dialog').modal('hide');
                // If we're logged in and there is a 'check_sdn_edit' function defined on page, check to see if we need to get settings
                if (auth_options.callback)
                    window[auth_options.callback](auth_options.callback_args);
                if (window.check_sdn_edit)
                    check_sdn_edit();
                if (auth_options.action_type == 'login' && auth_options.reload_after_auth)
                    window.location.reload();
                return;
            } else if (data.code == 0 && !data.authorized) {

                // Open dialog
                clearos_modal_infobox_open('sdn-login-dialog');
                // If user closes modal box, redirect to non-edit mode
                $('#sdn-login-dialog').on('hidden.bs.modal', function() {
                    if (auth_options.no_redirect_on_cancel)
                        return;
                    else if (auth_options.use_full_path_on_redirect)
                        window.location = my_location.fullpath;
                    else if (!my_location.default_controller && auth_options.use_full_path_on_redirect)
                        return;
                    window.location = '/app/' + my_location.basename;
                });

                // If email was submitted...reset was a success...
                if (data.email != undefined) {
                    $('#sdn-login-dialog-message-bar').html(
                        theme_clearos_info_box('info', lang_success + '!', lang_sdn_password_reset + ': <span style=\'font-weight: bold\'>' + data.email + '</span>')
                    );
                    $('#sdn-login-dialog-message-bar').show(200);
                    $('#sdn_password_group').show();
                    $('#sdn_lost_password_group').hide();
                    $('.autofocus').focus();
                    $('#sdn_login_action').text(lang_login);
                    return;
                }
                
                // Marketplace 1.1 sends back array of admins
                $.each(data.sdn_admins, function(key, value) {   
                    $('#sdn_username')
                    .append($('<option>', { value : value })
                    .text(value)); 
                });

            } else if (data.code == 10) {
                // Code 10 is an invalid email
                $('#sdn-login-dialog-message-bar').html(theme_clearos_info_box('warning', lang_warning, lang_sdn_email_invalid));
                $('#sdn-login-dialog-message-bar').show(200);
            } else if (data.code == 11) {
                // Code 11 is an email mismatch for lost password
                $('#sdn-login-dialog-message-bar').html(theme_clearos_info_box('warning', lang_warning, lang_sdn_email_mismatch));
                $('#sdn-login-dialog-message-bar').show(200);
            } else if (data.code > 0) {
                $('#sdn-login-dialog-message-bar').html(theme_clearos_info_box('warning', lang_warning, lang_sdn_password_invalid));
                $('#sdn-login-dialog-message-bar').show(200);
            } else if (data.code < 0) {
                $('#sdn-login-dialog-message-bar').html(theme_clearos_info_box('warning', lang_warning, data.errmsg));
                $('#sdn-login-dialog-message-bar').show(200);
                return;
            }
            $('.autofocus').focus();
        },
        error: function(xhr, text, err) {
            // Don't display any errors if ajax request was aborted due to page redirect/reload
            if (xhr['abort'] == undefined)
                theme_clearos_dialog_box('some-error', lang_warning, xhr.responseText.toString());
            $('#sidebar_setting_status').html('---');
        }
    });
}

function get_location_info()
{
    my_obj = new Object();
    my_obj.default_controller = true;
    my_obj.fullpath = document.location.pathname;
    regex = /\/app\/(\w+)\/.*/;
    path = document.location.pathname.match(regex);
    if (path == null) {
        my_obj.default_controller = false;
        regex = /\/app\/(\w+)$/;
        path = document.location.pathname.match(regex);
        if (path == null)
            console.log('Oh oh...could not determine app basename.');
        else
            my_obj.basename = path[1];
    } else {
        my_obj.basename = path[1];
        // Marketplace page where we can extract app name?
        regex = /\/app\/marketplace\/view\/(\w+)$/;
        app = document.location.pathname.match(regex);
        if (app != null)
            my_obj.app_name = app[1];
    }
    return my_obj;
}

function clearos_dialog_box(id, title, message, options) {
    return theme_clearos_dialog_box(id, title, message, options);
}

function clearos_dialog_close(obj) {
    return theme_clearos_dialog_close(obj);
}

function clearos_infobox(type, title, message, options)
{
    return theme_clearos_info_box(type, title, message, options);
}

function clearos_progress_bar(value, options)
{
    return theme_clearos_progress_bar(value, options);
}

function clearos_set_progress_bar(id, value, options)
{
    return theme_clearos_set_progress_bar(id, value, options);
}

function clearos_prevent_review() {
    clearos_dialog_box('review_error', '" . lang('base_warning') . "', '" . lang('marketplace_no_install_no_review') . "');
}

function clearos_loading(options)
{
    return theme_clearos_loading(options);
}

function clearos_add_review() {
    auth_options.no_redirect_on_cancel = true;
    auth_options.callback = 'clearos_display_review_form';
    clearos_is_authenticated();
}

function clearos_display_review_form() {
    clearos_modal_infobox_open('review-form');
    // Sometimes browser autocompletes this field
    $('#review-comment').val('');
}

function clearos_modal_infobox_open(id, options) {
    theme_modal_infobox_open(id, options);
}

function clearos_modal_infobox_close(id, options) {
    theme_modal_infobox_close(id, options);
}

function submit_review(update) {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/app/marketplace/ajax/add_review',
        data: 'ci_csrf_token=' + $.cookie('ci_csrf_token') + '&basename=' + $('#review-basename').val() + '&comment=' + $('#review-comment').val()
            + '&rating=' + $('#review-rating').val() + '&pseudonym=' + $('#review-pseudonym').val() + (update ? '&update=1' : ''),
        success: function(data) {
            if (data.code != 0) {
                // Check to see if there's already a review
                if (data.code == 8) {
                    $('#review-form').modal('hide');
                    $('#confirm-review-replace').modal('show');
                    return;
                }
                $('#review-message-bar').html(theme_clearos_info_box('warning', lang_warning, data.errmsg));
                $('#review-message-bar').show(200);
            } else {
                $('#review-form').modal('hide');
                var options = new Object();
                options.reload_on_close = true;
                clearos_dialog_box('submit_info', '" . lang('base_information') . "', data.status, options);
            }
        },
        error: function(xhr, text, err) {
            clearos_dialog_box('error', '" . lang('base_warning') . "', xhr.responseText.toString());
        }
    });
}

function peer_review(basename, dbid, approve) {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/app/marketplace/ajax/peer_review',
        data: 'ci_csrf_token=' + $.cookie('ci_csrf_token') + '&basename=' + basename + '&approve=' + approve + '&dbid=' + dbid,
        success: function(data) {
            if (data.code == 1) {
                clearos_is_authenticated();
            } else if (data.code != 0) {
                clearos_dialog_box('peer_review_error', '" . lang('base_warning') . "', data.errmsg);
            } else {
                if (approve > 0) {
                    // Already rated
                    if (data.updated_review != undefined) {
                        $('#agree_' + dbid).html(parseInt($('#agree_' + dbid).text()) + 1);
                        if (parseInt($('#disagree_' + dbid).text()) > 0)
                            $('#disagree_' + dbid).html(parseInt($('#disagree_' + dbid).text()) - 1);
                    } else if (data.new_review != undefined) {
                        $('#agree_' + dbid).html(parseInt($('#agree_' + dbid).text()) + 1);
                    }
                } else {
                    // New rating
                    if (data.updated_review != undefined) {
                        $('#disagree_' + dbid).html(parseInt($('#disagree_' + dbid).text()) + 1);
                        if (parseInt($('#agree_' + dbid).text()) > 0)
                            $('#agree_' + dbid).html(parseInt($('#agree_' + dbid).text()) - 1);
                    } else if (data.new_review != undefined) {
                        $('#disagree_' + dbid).html(parseInt($('#disagree_' + dbid).text()) + 1);
                    }
                }
            }
        },
        error: function(xhr, text, err) {
            clearos_dialog_box('error', '" . lang('base_warning') . "', xhr.responseText.toString());
        }
    });
}

function clearos_app_rating(basename, ratings) {
    var html = '';
    for (index = 0 ; index < ratings.length; index++) {
        ar = ratings[index];
        var title = ar.comment;
        if (title.indexOf('.') > 0) {
            title = title.substring(0, title.indexOf('.'));
        } else if (title.indexOf('\\n') > 0) {
            title = title.substring(0, title.indexOf('\\n'));
        }

        if (title == ar.comment)
            html += theme_rating_review(basename, ar.id, title, null, ar.rating, ar.pseudonym, ar.timestamp, ar.agree, ar.disagree); 
        else
            html += theme_rating_review(basename, ar.id, title, ar.comment, ar.rating, ar.pseudonym, ar.timestamp, ar.agree, ar.disagree); 
    }
    html += '<script type=\'text/javascript\'>' +
            '  $(\'a.review-action\').on(\'click\', function (e) {' +
            '    e.preventDefault();' +
            '    var parts = this.id.split(\'-\');' +
            '    clearos_is_authenticated();' +
            '    peer_review(parts[0], parts[1], (parts[2].match(/up/) ? 1 : 0));' +
            '  });' +
            '</script>'
    ;
    return html;
}

function clearos_get_app_screenshot(basename, index) {
    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: '/app/marketplace/ajax/get_app_screenshot/' + basename + '/' + index,
        success: function(data) {
            // Success..pass data to theme to update HTML.
            if (data.code == 0)
                $('#ss-' + basename + '_' + index).attr('src', data.location);
        },
        error: function(xhr, text, err) {
            console.log(xhr.responseText.toString());
        }
    });
}

function clearos_get_app_logo(basename, domid) {
    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: '/app/marketplace/ajax/get_app_logo/' + basename,
        success: function(data) {
            // Success..pass data to theme to update HTML.
            if (data.code == 0)
                theme_get_app_logo(domid, data);
        },
        error: function(xhr, text, err) {
            console.log(xhr.responseText.toString());
        }
    });
}

function clearos_screenshots(basename, screenshots) {
    return theme_screenshots(basename, screenshots);
}

function clearos_related_apps(type, list) {
    theme_related_app(type, list);
}

function clearos_marketplace_app_list(type, list, limit, total, options) {
    // theme_app function passes all information to theme to create HTML and place inside div
    theme_app(type, list, options);
    if (list.length < total) {
        // We need to populate the paginate widget
        
        var href = $(location).attr('href');
        var index = parseInt(href.substr(href.lastIndexOf('/') + 1));
        if (isNaN(index))
            index = 0;

        $('#paginate_next').before(theme_paginate('/app/marketplace/search/index', Math.ceil(total / limit) - 1, index));

        if ((Math.ceil(total / limit) - 1) > 1) {
            var prev = Math.max((index - 1), 0); 
            var next = Math.min((index + 1), (Math.ceil(total / limit) - 1)); 
            $('#paginate_prev').attr('href', '/app/marketplace/search/index/' + prev);
            $('#paginate_next').attr('href', '/app/marketplace/search/index/' + next);
            $('#paginate').buttonset();
            $('#marketplace_paginate_container').show();
        }
    }
}

$(document).on('click', '.sidebar-review-app', function(e) {
    e.preventDefault();
    clearos_add_review();
});

function clearos_load_lang(apps, obj) {
    payload = '&targets=' + apps; 
    if ($.isArray(apps)) {
        payload = ''; 
        foreach (basename in apps)
            payload += '&targets[]=' + basename;
    }
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/app/language/tags',
        data: 'ci_csrf_token=' + $.cookie('ci_csrf_token') + payload,
        success: function(data) {
            for (var key in data)
                obj[key] = data[key];
        },
        error: function(xhr, text, err) {
            clearos_dialog_box('error', '" . lang('base_warning') . "', xhr.responseText.toString());
        }
    });
}

/**
 * Converts a value to a human-readable format, e.g. integer IPs into quad-format.
 */

function clearos_human_readable(value, type) {
    if (type == 'ip') {
        var ip = value%256;

        for (var i = 3; i > 0; i--) {
            value = Math.floor(value/256);
            ip = value%256 + '.' + ip;
        }

        return ip;
    } else {
        return value;
    }
}

";
// vim: syntax=javascript ts=4

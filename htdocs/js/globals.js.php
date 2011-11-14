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
var lang_close = '" . lang("base_close") . "';
var lang_authenticate = '" . lang("base_authenticate") . "';
var lang_sdn_authentication_required = '" . lang("base_sdn_authentication_required") . "';
var lang_sdn_authentication_required_help = '" . lang("base_sdn_authentication_required_help") . "';
var lang_username = '" . lang("base_username") . "';
var lang_password = '" . lang("base_password") . "';
var lang_forgot_password = '" . lang("base_forgot_password") . "';
var lang_sdn_email = '" . lang("base_sdn_email") . "';
var lang_sdn_password_invalid = '" . lang("base_sdn_password_invalid") . "';
var lang_reset_password_and_send = '" . lang("base_reset_password_and_send") . "';
var lang_sdn_email_invalid = '" . lang("base_sdn_email_invalid") . "';
var lang_sdn_email_mismatch = '" . lang("base_sdn_email_mismatch") . "';
var lang_sdn_password_reset = '" . lang("base_sdn_password_reset") . "';
var lang_marketplace_connection_failure = '" . lang('marketplace_connection_failure') . "';
var lang_status = '" . lang('base_status') . "';
var lang_marketplace_redemption = '" . lang('marketplace_redemption') . "';
var lang_marketplace_expired_no_subscription = '" . lang('marketplace_expired_no_subsription') . "';
var lang_marketplace_billing_cycle_monthly = '" . lang('marketplace_billing_cycle_monthly') . "';
var lang_marketplace_billing_cycle_yearly = '" . lang('marketplace_billing_cycle_yearly') . "';
var lang_marketplace_billing_cycle_2_years = '" . lang('marketplace_billing_cycle_2_years') . "';
var lang_marketplace_billing_cycle_3_years = '" . lang('marketplace_billing_cycle_3_years') . "';
var lang_marketplace_billing_cycle = '" . lang('marketplace_billing_cycle') . "';
var lang_marketplace_renewal_date = '" . lang('marketplace_renewal_date') . "';
var lang_marketplace_upgrade = '" . lang('marketplace_upgrade') . "';
var lang_marketplace_sidebar_recommended_apps = '" . lang('marketplace_sidebar_recommended_apps') . "';
var lang_marketplace_recommended_apps = '" . lang('marketplace_recommended_apps') . "';

my_location = get_location_info();

$(document).ready(function() {
    theme_clearos_on_page_ready(my_location);
});

function clearos_is_authenticated() {
    theme_clearos_is_authenticated();
}

function clearos_dialog_box(id, title, message, options) {
    theme_clearos_dialog_box(id, title, message, options);
}

function get_location_info()
{
    my_obj = new Object();
    my_obj.default_controller = true;
    regex = /\/app\/(\w+)\/.*/;
    pathname = document.location.pathname.match(regex);
    if (pathname == null) {
        my_obj.default_controller = false;
        regex = /\/app\/(\w+)$/;
        pathname = document.location.pathname.match(regex);
        if (pathname == null)
            alert('Oh oh...could not determine app basename.');
        else
            my_obj.basename = pathname[1];
    } else {
        my_obj.basename = pathname[1];
    }
    return my_obj;
}
";
// vim: syntax=php ts=4

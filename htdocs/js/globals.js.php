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

header('Content-Type: application/x-javascript');

echo "
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

$(document).ready(function() {
    theme_clearos_on_page_ready();
});

function clearos_is_authenticated() {
    theme_clearos_is_authenticated();
}

function clearos_dialog_box(id, title, message, options) {
    theme_clearos_dialog_box(id, title, message, options);
}

";
// vim: syntax=php ts=4

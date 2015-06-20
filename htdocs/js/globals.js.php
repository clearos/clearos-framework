<?php

/**
 * Global javascript helper functions.
 *
 * The functions is javascript equivalent to the framework/shared/globals.php
 * file.  It's a place to put functions that we think should be in the global
 * scope.
 *
 * @category   Apps
 * @package    framework
 * @subpackage Javascript
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2012-2014 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/framework
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

///////////////////////////////////////////////////////////////////////////////
// M A I N
///////////////////////////////////////////////////////////////////////////////

header('Content-Type: application/x-javascript');

echo "

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

$(document).ready(function() {
    $('#wizard_nav_next').removeAttr('disabled');
    $('#wizard_nav_previous').removeAttr('disabled');
    $('#app-info-action').on('click', function (e) {
        e.preventDefault();
        clearos_modal_infobox_open('app-info-content');
    });
    $('#app-tips-action').on('click', function (e) {
        e.preventDefault();
        clearos_modal_infobox_open('app-tips-content');
    });
});
";
// vim: syntax=javascript ts=4

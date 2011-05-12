<?php

/**
 * Global ajax helpers.
 *
 * @category   ClearOS
 * @package    Base
 * @subpackage Javascript
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/framework/base/
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
// T R A N S L A T I O N S
///////////////////////////////////////////////////////////////////////////////

clearos_load_language('base');

///////////////////////////////////////////////////////////////////////////////
// J A V A S C R I P T  
///////////////////////////////////////////////////////////////////////////////

header('Content-Type:application/x-javascript');
?>

$(document).ready(function() {

    // Translations
    //-------------

    lang_running = '<?php echo lang("base_running"); ?>';
    lang_starting = '<?php echo lang("base_starting"); ?>';
    lang_stopping = '<?php echo lang("base_stopping"); ?>';
    lang_stopped = '<?php echo lang("base_stopped"); ?>';

    // Click Events
    //-------------

    $('#clearos_daemon_start').click(function() {
        startDaemon('squid');
        getData('squid', 1000);
    });

    $('#clearos_daemon_stop').click(function() {
        stopDaemon('squid');
        getData('squid', 1000);
    });

    // Main
    //-----

    $("#clearos_daemon_start").hide();
    $("#clearos_daemon_stop").hide();
    $('#clearos_daemon_status').html('');

    getData('squid', 5000);

    // Functions
    //----------

    function startDaemon(daemon) {
        $.ajax({
            url: 'base/daemon/start/squid',
            method: 'GET',
            dataType: 'json',
            success : function(payload) {
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
            }
        });
    }

    function stopDaemon(daemon) {
        $.ajax({
            url: 'base/daemon/stop/squid',
            method: 'GET',
            dataType: 'json',
            success : function(payload) {
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
            }

        });
    }

    function getData(daemon, timeout) {
    //    var url = 'base/daemon/status/' + String(daemon);
        var url = 'base/daemon/status/squid' + '/' + String(daemon);
        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success : function(payload) {
                showData(payload);
                window.setTimeout(getData, timeout);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                window.setTimeout(getData, timeout);
            }

        });
    }

    function showData(payload) {
        if (payload.status == 'running') {
            $("#clearos_daemon_start").hide();
            $("#clearos_daemon_stop").show();
            $("#clearos_daemon_status").html(lang_running);
        } else if (payload.status == 'stopped') {
            $("#clearos_daemon_start").show();
            $("#clearos_daemon_stop").hide();
            $("#clearos_daemon_status").html(lang_stopped);
        } if (payload.status == 'starting') {
            $("#clearos_daemon_start").hide();
            $("#clearos_daemon_stop").hide();
            $('#clearos_daemon_status').html(lang_starting + '<span class="theme-loading"></span>');
        } if (payload.status == 'stopping') {
            $("#clearos_daemon_start").hide();
            $("#clearos_daemon_stop").hide();
            $('#clearos_daemon_status').html(lang_stopping + '<span class="theme-loading"></span>');
        }
    }
});

// vim: syntax=javascript

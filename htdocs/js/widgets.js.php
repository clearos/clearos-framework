<?php

/**
 * Javascript helpers for theming.
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

// Anchors
//--------

function clearos_anchor(href, text, options) {
    return theme_anchor(href, text, options);
}

// Dialog box
//-----------

function clearos_dialog_box(id, title, message, options) {
    return theme_dialog_box(id, title, message, options);
}

function clearos_dialog_close(obj) {
    return theme_dialog_close(obj);
}

// Infobox
//---------

function clearos_infobox_warning(title, message, options)
{
    return theme_infobox('warning', title, message, options);
}

function clearos_infobox_info(title, message, options)
{
    return theme_infobox('info', title, message, options);
}

function clearos_modal_infobox_open(id, options) {
    theme_modal_infobox_open(id, options);
}

function clearos_modal_infobox_close(id, options) {
    theme_modal_infobox_close(id, options);
}

// Progress bar
//-------------

function clearos_progress_bar(value, options)
{
    return theme_progress_bar(value, options);
}

function clearos_set_progress_bar(id, value, options)
{
    return theme_set_progress_bar(id, value, options);
}

// Loading
//--------

function clearos_loading(options)
{
    return theme_loading(options);
}

// Loaded
//--------

function clearos_loaded(id)
{
    return theme_loaded(id);
}

// Screenshots
//------------

function clearos_screenshots(basename, screenshots) {
    return theme_screenshots(basename, screenshots);
}

// Related apps
//-------------

function clearos_related_apps(type, list) {
    theme_related_app(type, list);
}

// Summary table
//--------------

function clearos_summary_table(table_id, data, data_type, urls, highlight, sort, report_id) {
    return theme_summary_table(table_id, data, data_type, urls, highlight, sort, report_id);
}

// Chart
//------

function clearos_chart(chart_id, chart_type, data, format, series, series_labels, series_units, series_title, options) {
    return theme_chart(chart_id, chart_type, data, format, series, series_labels, series_units, series_title, options);
}

// Pie Chart
//----------

function clearos_pie_chart(chart_id, data, options) {
    return theme_chart(chart_id, 'pie', data, '', '', '', '', '', options);
}

";
// vim: syntax=javascript ts=4

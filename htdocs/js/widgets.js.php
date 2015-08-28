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

// Anchor
//-------

function clearos_anchor(href, text, options) {
    return theme_anchor(href, text, options);
}

// Anchors
//--------

function clearos_anchors(links, options) {
    return theme_anchors(links, options);
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

function clearos_infobox_success(title, message, options)
{
    return theme_infobox('success', title, message, options);
}

function clearos_infobox_critical(title, message, options)
{
    return theme_infobox('critical', title, message, options);
}

function clearos_modal_infobox_open(id, options) {
    theme_modal_infobox_open(id, options);
}

function clearos_modal_infobox_close(id, options) {
    theme_modal_infobox_close(id, options);
}

// Format a date/timestamp
//-------------------------

function clearos_format_date(value, format) {
    return theme_format_date(value, format);
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

// Marketplace (APP Rating)
//-------------------------

function clearos_star_rating(rating) {
    return theme_star_rating(rating);
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

// Hook to create key/value DOM
//-----------------------------

function clearos_key_value_pair(key, value, options) {
    return theme_key_value_pair(key, value, options)
}

// Hook to add key/value pair to sidebar
//--------------------------------------

function clearos_add_sidebar_pair(key, value, options) {
    theme_add_sidebar_pair(key, value, options);
}

// Summary table
//--------------

function clearos_summary_table(table_id, data, data_type, urls, highlight, sort, report_id) {
    return theme_summary_table(table_id, data, data_type, urls, highlight, sort, report_id);
}

// Enable symbol
//--------------

function clearos_enabled() {
    return theme_enabled_disabled(true);
}

// Disable symbol
//---------------

function clearos_disabled() {
    return theme_enabled_disabled(false);
}

// Chart
//------

function clearos_chart(chart_id, chart_type, data, data_titles, data_types, data_units, options) {
    return theme_chart(chart_id, chart_type, data, data_titles, data_types, data_units, options);
}

// Pie Chart
//----------

function clearos_pie_chart(chart_id, data, options) {
    return theme_chart(chart_id, 'pie', data, '', '', '', options);
}

";
// vim: syntax=javascript ts=4

<?php

/**
 * Webconfig page class.
 *
 * @category   Framework
 * @package    Application
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
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
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

use \clearos\framework\Logger as Logger;
use \clearos\framework\Config as Config;

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Webconfig page class.
 *
 * This class provides the mechanism for managing the type of a webconfig
 * page.  The view of a given ClearOS App is managed by the app developer,
 * while the view of the following is managed by this class:
 *
 * - Header 
 * - Footer
 * - Menu
 * - Summary
 * - Status messages
 *
 * This class handles the data management (e.g. parsing menu meta data), 
 * while the theme developer handles the look and feel.  This class also 
 * provides the following variables for use in their overall webconfig page:
 *
 * - login (if logged in)
 * - full name (if logged in)
 * - hostname
 * - registration status
 * - locale
 * - OS name
 * - OS version
 * - theme
 * - theme mode
 * - app 16x16 icon
 * - app 32x32 icon
 * - success message (e.g. firewall rule has been deleted)
 * - warning message (e.g. OpenVPN is running, but the firewall is not allowing connections)
 * - page type (default, splash, wizard(?))
 * - page title
 *
 * This class also handles exceptions.  When an exception occurs in a
 * controller, further processing is halted.  The exception is then shown
 * via the handle_exception() method.
 *
 * @category   Framework
 * @package    Application
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/
 */

class MY_Page
{
    ///////////////////////////////////////////////////////////////////////////////
    // C O N S T A N T S
    ///////////////////////////////////////////////////////////////////////////////

    const TYPE_CONFIGURATION = 'configuration';
    const TYPE_REPORT = 'report';
    const TYPE_SPLASH = 'splash';
    const TYPE_WIZARD = 'wizard';

    const MODE_CONTROL_PANEL = 'control_panel';
    const MODE_NORMAL = 'normal';

    ///////////////////////////////////////////////////////////////////////////////
    // V A R I A B L E S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * @var object framework instance
     */

    protected $framework = NULL;

    /**
     * @var array page information
     */

    public $data = array();

    /**
     * @var boolean form_only
     */

    public $form_only = FALSE;

    ///////////////////////////////////////////////////////////////////////////////
    // M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Page constructor.
     */

    public function __construct()
    {
        Logger::profile_framework(__METHOD__, __LINE__, 'Page Class Initialized');

        $this->framework =& get_instance();
    }

    /**
     * Loads the required theme files. 
     *
     * The theme hooks are loaded after the controller has been initialized.
     * - doctype.php
     * - head.php
     * - page.php
     * - widgets.php
     *
     * This is called by a CodeIgniter hook instead of the constructor since
     * the user session has not been initialized in the constructor.
     *
     * @access private
     * @return void
     */

    public function load_theme()
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        $theme_files = array('doctype.php', 'head.php', 'page.php', 'widgets.php');
        $path = Config::get_theme_path($this->framework->session->userdata('theme'));

        foreach ($theme_files as $file) {
            Logger::profile_framework(__METHOD__, __LINE__, "Loading theme file $file");
            $full_path = $path . '/core/' . $file;

            if (file_exists($full_path))
                include $full_path;
            else
                echo "<p class='alert'>Theme file is missing: $file</p>";
        }
    }

    /**
     * Handles status added message.
     *
     * @return void
     */

    public function set_status_added()
    { 
        Logger::profile_framework(__METHOD__, __LINE__);

        $message = 'Added.'; // FIXME translate 

        $this->framework->session->set_userdata('status_success', $message);
    }

    /**
     * Handles status deleted message.
     *
     * @return void
     */

    public function set_status_deleted()
    { 
        Logger::profile_framework(__METHOD__, __LINE__);

        $message = 'Delete completed.'; // FIXME translate 

        $this->framework->session->set_userdata('status_success', $message);
    }

    /**
     * Handles status updated message.
     *
     * @return void
     */

    public function set_status_updated()
    { 
        Logger::profile_framework(__METHOD__, __LINE__);

        $message = 'System Updated'; // FIXME translate 

        $this->framework->session->set_userdata('status_success', $message);
    }

    /**
     * Handles a page success message.
     *
     * @param string $message success message
     *
     * @return void
     */

    public function set_status_success($message)
    { 
        Logger::profile_framework(__METHOD__, __LINE__);

        $this->framework->session->set_userdata('status_success', $message);
    }

    /**
     * Redirects depending on theme mode.
     *
     * @param string $redirect redirect URL
     *
     * @return void
     */

    public function theme_redirect($redirect)
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        // Non-intuitive, see view_forms for form_only discussion
        if ($this->form_only)
            return;

        if ($this->framework->session->userdata['theme_mode'] !== self::MODE_CONTROL_PANEL)
            redirect($redirect);
    }

    /**
     * Displays delete confirmation.
     *
     * @return view
     */

    public function view_confirm_delete($confirm, $cancel, $items, $options = array())
    {
        Logger::profile_framework(__METHOD__, __LINE__);
   
        if (empty($this->data))
            $this->_load_meta_data();

        $app = $this->framework->uri->segment(1);

        $message = isset($options['message']) ? $options['message'] : 'Are you sure you want to delete the following?'; // FIXME translate

        $this->data['title'] = 'Confirm Delete'; // FIXME: translate
        $this->data['app_view'] = theme_confirm_delete($confirm, $cancel, $items, $message, $options);
        $this->data['page_help'] = $this->_get_help_view($app);
        $this->data['page_summary'] = $this->_get_summary_view($app);
        $this->data['page_report'] = $this->_get_report_view($app);

        $this->_display_page();
    }

    /**
     * Displays a page with a single form.
     *
     * @return view
     */

    public function view_form($form, $data, $title, $options = array())
    {
        Logger::profile_framework(__METHOD__, __LINE__);

/*
        // FIXME: what to do with help and summary widgets 
        if ($this->framework->session->userdata['theme_mode'] === self::MODE_CONTROL_PANEL) {
        }
*/

        if (empty($this->data))
            $this->_load_meta_data();

        $type = isset($options['type']) ? $options['type'] : MY_Page::TYPE_CONFIGURATION;

        $this->data['title'] = $title;
        $this->data['type'] = $type;

        // Non-intuitive: see view_forms for form_only explanation
        
        if ($this->form_only) {
            $this->framework->load->view($form, $data);
        } else {
            $this->data['app_view'] = $this->framework->load->view($form, $data, TRUE);
            $this->data['page_help'] = $this->_get_help_view($form);
            $this->data['page_summary'] = $this->_get_summary_view($form);
            $this->data['page_report'] = $this->_get_report_view($form);

            $this->_display_page();
        }
    }

    /**
     * Displays a page with multiple forms.
     *
     * @return view
     */

    public function view_forms($forms, $title)
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        $this->_load_meta_data();

        $this->data['title'] = $title;

        // Control panel style
        //--------------------

        if ($this->framework->session->userdata['theme_mode'] === self::MODE_CONTROL_PANEL) {

            $app_data = $this->_load_app_data();

            foreach ($forms as $form) {
                $basename = preg_replace('/.*\//', '', $form);
                $data[$form]['title'] = $app_data['controllers'][$basename]['title'];
            }

            // Add common widgets
            $basename = preg_replace('/\/.*/', '', $form);
            $data[$basename . '/summary']['title'] = 'Summary'; // FIXME: Translate
            $data[$basename . '/help']['title'] = 'Help'; // FIXME: Translate

            $this->data['app_view'] = theme_control_panel($data);

        // Full desktop style
        //-------------------

        } else {
            // Non-intuitive, but this saves app developers from handling a 
            // useless variable in their controllers.  The form_only variable
            // is set to TRUE to indicate that only the raw form should be 
            // loaded (no headers, no footers, etc.).

            $this->form_only = TRUE; 

            ob_start();

            foreach ($forms as $form) {
                $basename = preg_replace('/.*\//', '', $form);

                $this->framework->load->module($form);
                $this->framework->$basename->index('view');
            }

            $this->data['app_view'] = ob_get_clean();

            // Now we set form_only back to the default
            $this->form_only = FALSE; 
        }

        $this->data['page_help'] = $this->_get_help_view($form);
        $this->data['page_summary'] = $this->_get_summary_view($form);
        $this->data['page_report'] = $this->_get_report_view($form);

        $this->_display_page();
    }

    /**
     * Displays the exception view.
     *
     * @param Exception $exception exception
     *
     * @return void
     */

    public function view_exception($exception)
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        if (empty($this->data))
            $this->_load_meta_data();

        // FIXME: might want to make this a splash type
        $this->data['type'] = MY_Page::TYPE_REPORT;
        $this->data['title'] = 'Ooops';
        $this->data['app_view'] = theme_dialog_warning($exception->GetMessage());

        $this->_display_page();
    }

    ///////////////////////////////////////////////////////////////////////////////
    // F R I E N D  M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////
    //
    // These are for internal framework use and not intended for app developers.
    //
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Display help box.
     *
     * @access private
     */

    public function view_help($form)
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        $this->data = array();
        $this->_load_meta_data();

        $this->data['title'] = 'Help'; // FIXME
        $this->data['type'] = MY_Page::TYPE_CONFIGURATION;
        $this->data['app_view'] = $this->_get_help_view($form);

        $this->_display_page();
    }

    /**
     * Display report box.
     *
     * @access private
     */

    public function view_report($form)
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        $this->data = array();
        $this->_load_meta_data();

        $this->data['title'] = 'Dashboard Report'; // FIXME
        $this->data['type'] = MY_Page::TYPE_CONFIGURATION;
        $this->data['app_view'] = $this->_get_report_view($form);

        $this->_display_page();
    }

    /**
     * Display summary box.
     *
     * @access private
     */

    public function view_summary($form)
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        $this->data = array();
        $this->_load_meta_data();

        $this->data['title'] = 'Summary'; // FIXME
        $this->data['type'] = MY_Page::TYPE_CONFIGURATION;
        $this->data['app_view'] = $this->_get_summary_view($form);

        $this->_display_page();
    }

    ///////////////////////////////////////////////////////////////////////////////
    // P R I V A T E  M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Returns the HTML head section.
     *
     * @param array $page_data page data
     *
     * @return string HTML head section
     */

    protected function _build_page_head($page_data)
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        // Adding hostname to the title is handy when managing multiple systems
        //---------------------------------------------------------------------

        $title = $page_data['title'];

        if ($this->framework->session->userdata('hostname'))
            $title = $this->framework->session->userdata('hostname') . " - " . $title;

        // Determine the version to use (trunk, 6.0, etc)
        //-----------------------------------------------

        $segments = explode('/', $_SERVER['PHP_SELF']);
        $app = $segments[2];

        $doc_base = clearos_app_base($app) . '/htdocs/';
        $app_url = Config::get_app_url($app);
        $theme_path = Config::get_theme_url($this->framework->session->userdata('theme'));

        // Add page-specific head links
        //-----------------------------

        $css =  $app . '.css';
        $js = $app . '.js.php';

        $page_auto_head = '';

        if (file_exists($doc_base . '/' . $js))
            $page_auto_head .= "<script type='text/javascript' src='" . $app_url . '/' . $js . "'></script>\n";

        if (file_exists($doc_base . '/' . $css))
            $page_auto_head .= "<link type='text/css' href='" . $app_url . '/' . $css ."' rel='stylesheet'>\n";

        // <html>
        //-------------------
        
        $head = "<html dir='" . $this->framework->session->userdata('textdir') . "'>\n\n";

        // <head> commom
        //-------------------

        $head .= "<!-- Head -->
<head>

<!-- Basic Head Information -->
<title>$title</title>
<meta http-equiv='Content-Type' content='text/html; charset=" . $this->framework->session->userdata('charset') . "'>

<!-- Jquery -->
<script type='text/javascript' src='/js/jquery-1.4.4.min.js'></script>
";

        // <head> extras defined in theme (head.php)
        //------------------------------------------

        $head .= theme_page_head($theme_path);

        // <head> extras defined in app
        //------------------------------------------

        if ($page_auto_head)
            $head .= "<!-- Page-specific Head -->\n$page_auto_head\n";

        // </head> all done
        //------------------------------------------

        $head .= "</head>\n\n";

        return $head;
    }

    /**
     * Displays the webconfig page.
     *
     * @return string HTML of webconfig page
     */

    protected function _display_page()
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        echo theme_page_doctype() . "\n";
        echo $this->_build_page_head($this->data);
        echo theme_page($this->data);
    }

    /**
     * Returns the help view.
     *
     * @return string HTML for help view
     */

    public function _get_help_view($form)
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        $data = $this->_load_app_data();

        // FIXME: Move this to a driver package
        if (empty($data['user_guide_url']))
            $data['user_guide_url'] = 'http://www.clearcenter.com/support/documentation/FIXME';

        if (empty($data['support_url']))
            $data['support_url'] = 'http://www.clearcenter.com/getsupport/FIXME';

        return theme_help_box($data);
    }

    /**
     * Returns the report view.
     *
     * Returns NULL if no report exists for the given form.
     *
     * @return string HTML for report box
     */

    protected function _get_report_view($form)
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        $basename = preg_replace('/.*\//', '', $form);

        $this->framework->load->module($form);

        if (! method_exists($this->framework->$basename, 'report'))
            return;

        ob_start();
        $this->framework->$basename->report();
        $report = ob_get_clean();

        return $report;
    }

    /**
     * Returns the summary view.
     *
     * @return string HTML for summary view
     */

    public function _get_summary_view($form)
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        $data = $this->_load_app_data();

        $data['tooltip'] = (isset($data['controllers'][$form]['tooltip'])) ? $data['controllers'][$form]['tooltip'] : '';

        // FIXME: fake data here
        $data['subscription_expiration'] = 'July 1, 2011';
        $data['install_status'] = 'Update available';
        $data['marketplace_chart'] = "
<div id='theme-chart-info-box' style='height:200px; width:200px;'></div>
<script type='text/javascript'>
$.jqplot.config.enablePlugins = true;
$.jqplot('theme-chart-info-box', [[[1, 2],[3,5.12],[5,13.1],[7,33.6],[9,85.9],[11,219.9]]]);
</script>
";

        return theme_summary_box($data);
    }

    /**
     * Returns app data in an array.
     *
     * @return array app meta data
     */

    protected function _load_app_data($app_name = NULL)
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        if (is_null($app_name)) {
            $segments = explode('/', $_SERVER['PHP_SELF']);
            $app_name = $segments[2];
        }

        $info_file = clearos_app_base($app_name) . '/deploy/info.php';

        if (file_exists($info_file)) {

            // Load metadata file
            clearos_load_language($app_name);
            include $info_file;

            // Add timestamp
            $stat = stat($info_file);
            $app['modified'] = $stat['ctime'];

            return $app;
        }
    }

    /**
     * Returns menu data in an array.
     *
     * @return array menu meta data
     */

    protected function _load_menu_data()
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        // Create a list of apps to check
        //-------------------------------

        $apps_list = array();

        foreach (Config::$apps_paths as $path) {
            // TODO: remove - it's just a temporary workaround for a pre-release version
            $path = (preg_match('/apps$/', $path)) ? $path : $path . '/apps';

            $raw_list = scandir($path);
            $most_recent = 0;

            foreach ($raw_list as $dir) {
                if (! preg_match('/^\./', $dir)) {
                    $info_file = clearos_app_base($dir) . '/deploy/info.php';
                    if (file_exists($info_file)) {
                        $apps_list[] = $dir;

                        $stat = stat($info_file);

                        if ($stat['ctime'] > $most_recent)
                            $most_recent = $stat['ctime'];
                    }
                }
            }
        }

        // If timestamps are okay, use the cache file
        //-------------------------------------------

        $stat = stat(CLEAROS_TEMP_DIR . '/menu_cache');
        $cache_time = $stat['ctime'];

        if ($cache_time > $most_recent)
            return unserialize( file_get_contents(CLEAROS_TEMP_DIR . '/menu_cache') );

        // Load menu order preferences
        //----------------------------

        $order = array(
            lang('base_category_marketplace') => '010',
            lang('base_category_server')  => '020',
            lang('base_category_network') => '030',
            lang('base_category_gateway') => '040',
            lang('base_category_system')  => '050',
        );

        // Create an array with the sort key
        //----------------------------------

        $sorted = array();

        foreach ($apps_list as $app) {
            $app = $this->_load_app_data($app);

            if (! isset($app['basename'])) 
                continue;

            // If this is just a library, skip it
            if (isset($app['menu_enabled']) && (!$app['menu_enabled']))
                continue;

            $primary_sort = empty($order[$app['category']]) ? '500' : $order[$app['category']];
            $secondary_sort = empty($order[$app['subcategory']]) ? $app['subcategory'] : $order[$app['subcategory']];
            $page_sort = empty($app['priority']) ? '500' : $app['priority'];

            $menu_info = array();

            $menu_info['/app/' . $app['basename']] = array(
                'title' => $app['name'],
                'category' => $app['category'],
                'subcategory' => $app['subcategory'],
            );

            $sorted[$primary_sort . '.' . $secondary_sort . '.' . $page_sort . '.' . $app['name']] = $menu_info;
        }

        // Use the sorted array to generate the menu array
        //------------------------------------------------

        ksort($sorted);

        $menu_data = array();

        foreach ($sorted as $sort_key => $sort_details) {
            foreach ($sort_details as $url => $details)
                $menu_data[$url] = $details;
        }

        // Cache the data and return it
        //-----------------------------

        file_put_contents(CLEAROS_TEMP_DIR . '/menu_cache', serialize($menu_data));

        return $menu_data;
    }

    /**
     * Loads the page meta data into the data class variable.
     *
     * @return void
     */

    protected function _load_meta_data()
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        $view_data = $this->_load_view_data();
        $menu_data['menus'] = $this->_load_menu_data();
        $session_data = $this->_load_session_data();

        $this->data = array_merge($this->data, $view_data, $session_data, $menu_data);
    }

    /**
     * Returns page session data in an array.
     *
     * @return array session meta data
     */

    protected function _load_session_data()
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        $session_data = array();

        // Grab all the session values
        //----------------------------

        foreach ($this->framework->session->userdata as $key => $value)
            $session_data[$key] = $value;

        // The "status_success" message is a flash value... delete it
        //-----------------------------------------------------------

        if ($this->framework->session->userdata('status_success'))
            $this->framework->session->unset_userdata('status_success');

        return $session_data;
    }

    /**
     * Returns view page data in an array.
     *
     * @return array view meta data
     */

    protected function _load_view_data()
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        $view_data = array();

        if (empty($this->data['type']))
            $view_data['type'] = MY_Page::TYPE_CONFIGURATION;

        return $view_data;
    }
}

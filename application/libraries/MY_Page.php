<?php

/**
 * Webconfig page constants class.
 *
 * @category   Framework
 * @package    Application
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2017 ClearFoundation
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
use \clearos\apps\base\Access_Control as Access_Control;
use \clearos\apps\base\Install_Wizard as Install_Wizard;
use \clearos\apps\events\Events as Events;
use \clearos\apps\registration\Registration as Registration;

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Webconfig page constants class.
 *
 * Due to 2.0 to 3.0 CodeIgniter changes, the "MY_Page" class was renambed
 * to "Page".  However, the My_Page::XYZ constants are used in quite a few
 * apps, e.g.:
 *
 *    $options['type'] = MY_Page::TYPE_WIDE_CONFIGURATION;
 *
 * For sanity, these constants have been preserved.
 *
 * @category   Framework
 * @package    Application
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2017 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/
 */

class MY_Page
{
    ///////////////////////////////////////////////////////////////////////////////
    // C O N S T A N T S
    ///////////////////////////////////////////////////////////////////////////////

    // In CodeIgniter 3, this needed to be changed to "Page", but we still 
    // need the MY_Page::xyz constants.

    const TYPE_CONFIGURATION = 'configuration';
    const TYPE_WIDE_CONFIGURATION = 'wide_configuration';
    const TYPE_LOGIN = 'login';
    const TYPE_2FACTOR_AUTH = '2factor_auth';
    const TYPE_REPORT = 'report'; // TODO: deprecated, remove
    const TYPE_REPORTS = 'reports';
    const TYPE_REPORT_OVERVIEW = 'report_overview';
    const TYPE_SPOTLIGHT = 'spotlight';
    const TYPE_SPLASH = 'splash';
    const TYPE_SPLASH_ORGANIZATION = 'splash_org';
    const TYPE_WIZARD = 'wizard';
    const TYPE_CONSOLE = 'console';
    const TYPE_DASHBOARD = 'dashboard';
    const TYPE_DASHBOARD_WIDGET = 'dashboard_widget';
    const TYPE_EXCEPTION = 'exception';

    const MODE_CONTROL_PANEL = 'control_panel';
    const MODE_NORMAL = 'normal';

    ///////////////////////////////////////////////////////////////////////////////
    // M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Page constructor.
     */

    public function __construct()
    {}
}

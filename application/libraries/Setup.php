<?php

/**
 * Webconfig setup class.
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

$bootstrap = isset($_ENV['CLEAROS_BOOTSTRAP']) ? $_ENV['CLEAROS_BOOTSTRAP'] : '/usr/clearos/framework/shared';
require_once $bootstrap . '/bootstrap.php';

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

use \clearos\apps\base\Webconfig as Webconfig;
use \clearos\framework\Logger as Logger;

clearos_load_library('base/Webconfig');

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Webconfig setup class.
 *
 * This class handles the setup wizard.
 *
 * @category   Framework
 * @package    Application
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/
 */

class MY_Setup
{
    ///////////////////////////////////////////////////////////////////////////////
    // V A R I A B L E S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * @var object framework instance
     */

    protected $framework = NULL;

    ///////////////////////////////////////////////////////////////////////////////
    // M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Setup constructor.
     */

    public function __construct()
    {
        Logger::profile_framework(__METHOD__, __LINE__, 'Setup Class Initialized');

        $this->framework =& get_instance();
    }

    /**
     * TODO
     *
     * @return void
     */
    public function check()
    {
        Logger::profile_framework(__METHOD__, __LINE__);

        // Return right away if already on the setup/upgrade wizard
        //---------------------------------------------------------

        // FIXME
        if ($_SERVER['PHP_SELF'] === '/app/base/setup')
            return;

        if ($_SERVER['PHP_SELF'] === '/app/base/access')
            return;

        if ($_SERVER['PHP_SELF'] === '/app/base/login')
            return;

        // Check to see if setup/upgrade wizard is required
        //-------------------------------------------------

        try { 
            $webconfig = new Webconfig();
            // $setup_required = $webconfig->GetSetupState();
            $setup_required = FALSE;
        } catch (Exception $e) {
            echo $e->GetMessage();
            exit();
        } 

        if ($setup_required)
            redirect('base/setup');
    }
}

// vim: syntax=php ts=4
?>

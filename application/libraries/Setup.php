<?php

///////////////////////////////////////////////////////////////////////////////
//
// Copyright 2010 ClearFoundation
//
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

/**
 * Webconfig setup class.
 *
 * @package Framework
 * @author {@link http://www.clearfoundation.com/ ClearFoundation}
 * @license http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @copyright Copyright 2003-2010 ClearFoundation
 */

///////////////////////////////////////////////////////////////////////////////
// B O O T S T R A P
///////////////////////////////////////////////////////////////////////////////

$bootstrap = isset($_ENV['CLEAROS_BOOTSTRAP']) ? $_ENV['CLEAROS_BOOTSTRAP'] : '/usr/clearos/framework/shared';
require_once($bootstrap . '/bootstrap.php');

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

clearos_load_library('base/Engine');

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Webconfig setup class.
 *
 * This class handles the setup wizard.
 *
 * @return  void
 */

class MY_Setup extends Engine
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
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__, 'Setup Class Initialized');

		$this->framework =& get_instance();
	}

	public function check()
	{
		ClearOsLogger::ProfileFramework(__METHOD__, __LINE__);

		// Return right away if already on the setup/upgrade wizard
		//---------------------------------------------------------

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

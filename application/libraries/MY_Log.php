<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ClearOS log override class.
 *
 * @category   Framework
 * @package    Base
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2013 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/framework/
 */

///////////////////////////////////////////////////////////////////////////////
// B O O T S T R A P
///////////////////////////////////////////////////////////////////////////////

$bootstrap = getenv('CLEAROS_BOOTSTRAP') ? getenv('CLEAROS_BOOTSTRAP') : '/usr/clearos/framework/shared';
require_once($bootstrap . '/bootstrap.php');

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

use \clearos\framework\Config as Config;
use \clearos\framework\Error as Error;
use \clearos\framework\Logger as Logger;

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * ClearOS log override class.
 *
 * @category   Framework
 * @package    Base
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2013 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/framework/
 */

class MY_Log extends CI_Log {

    /**
     * Constructor
     */

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * ClearOS log writing override.
     *
     * Override type: changed method
     *
     * Use a custom ClearOS logging methodology.
     *
     * @param string  the error level
     * @param string  the error message
     * @param boolean whether the error is a native PHP error
     *
     * @return boolean
     */

    public function write_log($level = 'error', $msg, $php_error = FALSE)
    {
        if ($this->_enabled === FALSE)
        {
            return FALSE;
        }

        $level = strtoupper($level);

        if ( ! isset($this->_levels[$level]) OR ($this->_levels[$level] > $this->_threshold))
        {
            return FALSE;
        }

        // Pull in ClearOS logging infrastructure
        if (!Config::$debug_mode) 
            return FALSE;

        // See Error.php for explanation of error code handling
        require_once(Config::get_framework_path() . '/shared/libraries/Logger.php');
        require_once(Config::get_framework_path() . '/shared/libraries/Error.php');

        if ($level === 'ERROR') {
            $clearos_level = CLEAROS_ERROR;
            $type = Error::TYPE_ERROR;
        } else if ($level === 'DEBUG') {
            $clearos_level = CLEAROS_DEBUG;
            $type = Error::TYPE_PROFILE;
        } else if ($level === 'INFO') {
            $clearos_level = CLEAROS_INFO;
            $type = Error::TYPE_ERROR;
        } else {
            $clearos_level = CLEAROS_ERROR;
            $type = Error::TYPE_ERROR;
        }

        $error = new Error($clearos_level, $msg, 'clearos\framework\Core', 0, NULL, $type);

        Logger::Log($error);

        /*
        $trace = debug_backtrace();
        foreach ($trace as $item) {
            $error = new ClearOsError($clearos_level, "backtrace", $item['file'], $item['line'], NULL, $type);
            ClearOsLogger::Log($error);
        }
        */

        return TRUE;
    }
}

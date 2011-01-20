<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2010, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

///////////////////////////////////////////////////////////////////////////////
// B O O T S T R A P 
///////////////////////////////////////////////////////////////////////////////

$bootstrap = getenv('CLEAROS_BOOTSTRAP') ? getenv('CLEAROS_BOOTSTRAP') : '/usr/clearos/framework/shared';
require_once($bootstrap . '/bootstrap.php');

use \clearos\framework\Config as Config;
use \clearos\framework\Error as Error;
use \clearos\framework\Logger as Logger;

// ------------------------------------------------------------------------

/**
 * Logging Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Logging
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/general/errors.html
 */
class CI_Log {

	var $log_path;
	var $_threshold	= 1;
	var $_date_fmt	= 'Y-m-d H:i:s';
	var $_enabled	= TRUE;
	var $_levels	= array('ERROR' => '1', 'DEBUG' => '2',  'INFO' => '3', 'ALL' => '4');

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$config =& get_config();

		$this->log_path = ($config['log_path'] != '') ? $config['log_path'] : BASEPATH.'logs/';

		if ( ! is_dir($this->log_path) OR ! is_really_writable($this->log_path))
		{
			$this->_enabled = FALSE;
		}

		if (is_numeric($config['log_threshold']))
		{
			$this->_threshold = $config['log_threshold'];
		}

		if ($config['log_date_format'] != '')
		{
			$this->_date_fmt = $config['log_date_format'];
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Write Log File
	 *
	 * Generally this function will be called using the global log_message() function
	 *
	 * @access	public
	 * @param	string	the error level
	 * @param	string	the error message
	 * @param	bool	whether the error is a native PHP error
	 * @return	bool
	 */
	function write_log($level = 'error', $msg, $php_error = FALSE)
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

		if (!empty(Config::$clearos_devel_versions['framework']))
			$version = Config::$clearos_devel_versions['framework'];
		else
			$version = '';

		// See Error.php for explanation of error code handling
		require_once(Config::$framework_path . '/' . $version . '/shared/libraries/Logger.php');
		require_once(Config::$framework_path . '/' . $version . '/shared/libraries/Error.php');

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

		$error = new Error($clearos_level, $msg, 'Framework', 0, null, $type);

		Logger::Log($error);

		/*
		$trace = debug_backtrace();
		foreach ($trace as $item) {
			$error = new ClearOsError($clearos_level, "backtrace", $item['file'], $item['line'], null, $type);
			ClearOsLogger::Log($error);
		}
		*/

		return;
		// ClearFoundation -- we're done

		$filepath = $this->log_path.'log-'.date('Y-m-d').EXT;
		$message  = '';

		if ( ! file_exists($filepath))
		{
			$message .= "<"."?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?".">\n\n";
		}

		if ( ! $fp = @fopen($filepath, FOPEN_WRITE_CREATE))
		{
			return FALSE;
		}

		$message .= $level.' '.(($level == 'INFO') ? ' -' : '-').' '.date($this->_date_fmt). ' --> '.$msg."\n";

		flock($fp, LOCK_EX);
		fwrite($fp, $message);
		flock($fp, LOCK_UN);
		fclose($fp);

		@chmod($filepath, FILE_WRITE_MODE);
		return TRUE;
	}

}
// END Log Class

/* End of file Log.php */
/* Location: ./system/libraries/Log.php */

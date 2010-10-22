<?php

///////////////////////////////////////////////////////////////////////////////
//
// Copyright 2006, 2010 ClearFoundation
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
 * ClearOS logger class. 
 *
 * @package Framework
 * @author {@link http://www.foundation.com/ ClearFoundation}
 * @license http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @copyright Copyright 2006, 2010 ClearFoundation
 */ 

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

require_once('ClearOsError.php');

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * ClearOS logger class. 
 *
 * @package Framework
 * @author {@link http://www.foundation.com/ ClearFoundation}
 * @license http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @copyright Copyright 2006, 2010 ClearFoundation
 */ 

class ClearOsLogger
{
	/**
	 * ClearOsLogger constructor.
	 */

	private function __construct()
	{}

	/**
	 * Logs an error.
	 *
	 * @param ClearOsError $error error object
	 * @return void
	 */

	public static function Log(ClearOsError $error)
	{
		static $basetime = 0;

		// Set the log variables
		$errno = $error->GetCode();
		$errstring = $error->GetCodeString();
		$errmsg = $error->GetMessage();
		$file = $error->GetTag();
		$line = $error->GetLine();
		$context = $error->GetContext();
		$caught = $error->IsCaught();
		$type = $error->GetType();
		$typestring = $error->GetTypeString();

		// In debug mode, all errors are logged. In production mode, only important
		// messages are logged.

		if (! COMMON_DEBUG_MODE) {
			if ($type == ClearOsError::TYPE_EXCEPTION) {
 				if ($errno <= ClearOsError::CODE_WARNING)
					return;
			} else if ($type == ClearOsError::TYPE_ERROR) {
				// if (($errno === E_NOTICE) || ($errno === E_STRICT))
				//	return;
				// TODO: things like ldap_read generate errors... but that's
				// an expected error.  Unfortunately, it still gets logged
				return;
			}
		}

		// Specify log line format
		$logline = sprintf("$typestring: %s: %s (%d): %s", $errstring, preg_replace("/.*\//", "", $file), $line, $errmsg);

		// FIXME -- ignore strict errors coming out of CodeIgniter for now.
		if (($errno === E_STRICT) && preg_match('/\/framework\//', $file))
			return;

		// Perform extra goodness in debug mode
		if (COMMON_DEBUG_MODE) {
			// Append timestamp to log line
			if ($basetime == 0) {
				$basetime = microtime(true);
				$timestamp = 0;
			} else {
				$currenttime = microtime(true);
				$timestamp = microtime(true) - $basetime;
			}

			$logline = sprintf("%.4f: %s", round($timestamp, 4),  $logline);

			// Log messages to standard out when in command-line mode
			if (ini_get('display_errors') && preg_match('/cli/', php_sapi_name())) {
				echo "$logline\n";
			}

			// Log messages to custom log file (if set) and standard out on
			if (ini_get('error_log')) {
				date_default_timezone_set("EST");
				$timestamp = date("M j G:i:s T Y");
				error_log("{$timestamp}: $logline\n", 3, ini_get('error_log'));

				foreach ($error->getTrace() as $traceinfo) {
					// Backtrace log format
					$logline = sprintf("$typestring: debug backtrace: %s (%d): %s",
									   preg_replace("/.*\//", "", $traceinfo["file"]),
									   $traceinfo["line"],
									   $traceinfo["function"]);
					error_log("{$timestamp}: $logline\n", 3, ini_get('error_log'));
				}
			}
		} else {
			// Log errors to syslog
			openlog("engine", LOG_NDELAY, LOG_LOCAL6);
			syslog(LOG_INFO, $logline);

			// Log backtrace
			foreach ($error->getTrace() as $traceinfo) {
				// Backtrace log format
				$logline = sprintf("$typestring: debug backtrace: %s (%d): %s",
								   preg_replace("/.*\//", "", $traceinfo["file"]),
								   $traceinfo["line"],
								   $traceinfo["function"]);
				syslog(LOG_INFO, $logline);
			}

			closelog();
		}
	}

	/**
	 * Logs an exception.
	 *
	 * @param Exception $exception exception object
	 * @param boolean $iscaught set to true if exception was explicitly caught
	 * @return void
	 */

	public static function LogException(Exception $exception, $iscaught = TRUE)
	{
		ClearOsLogger::Log(
			new ClearOsError(
				$exception->getCode(),
				$exception->getMessage(),
				$exception->getFile(),
				$exception->getLine(),
				"",
				ClearOsError::TYPE_EXCEPTION,
				$iscaught,
				$exception->getTrace()
			)
		);
	}

	/**
	 * Logs to syslog.
	 *
	 * @param string $tag prefix for log message
	 * @param string $message short and informative message
	 * @return void
	 */

	public static function Syslog($tag, $message)
	{
		openlog($tag, LOG_NDELAY, LOG_LOCAL6);
		syslog(LOG_INFO, $message);
		closelog();
	}

	/**
	 * Logs profiling information.
	 *
	 * @param string $tag prefix for log message
	 * @param string $line line number
	 * @param string $message short and informative message
	 */

	public static function Profile($tag, $line, $message = 'called')
    {
		$error = new ClearOsError(ClearOsError::CODE_DEBUG, $message, $tag, $line, null, ClearOsError::TYPE_PROFILE);
        ClearOsLogger::Log($error);
    }
}

// vim: syntax=php ts=4
?>

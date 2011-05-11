<?php

/**
 * ClearOS logger class. 
 *
 * @category   Framework
 * @package    Shared
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/framework/
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
// N A M E S P A C E
///////////////////////////////////////////////////////////////////////////////

namespace clearos\framework;

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

use \Exception as Exception;
use \clearos\framework\Error as Error;

require_once 'Error.php';

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * ClearOS logger class. 
 *
 * @category   Framework
 * @package    Shared
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/framework/
 */

class Logger
{
    /**
     * Logs an error.
     *
     * @param Error $error error object
     *
     * @return void
     */

    public static function log(Error $error)
    {
        static $basetime = 0;

        // Set the log variables
        $errno = $error->get_code();
        $errstring = $error->get_code_string();
        $errmsg = $error->get_message();
        $file = $error->get_tag();
        $line = $error->get_line();
        $context = $error->get_context();
        $type = $error->get_type();
        $typestring = $error->get_type_string();

        // In debug mode, all errors are logged. In production mode, only important
        // messages are logged.

        if (! getenv('CLEAROS_BOOTSTRAP')) {
            if ($type == Error::TYPE_EXCEPTION) {
                 if ($errno <= Error::CODE_WARNING)
                    return;
            } else if ($type == Error::TYPE_ERROR) {
                // if (($errno === E_NOTICE) || ($errno === E_STRICT))
                //    return;
                // TODO: things like ldap_read generate errors... but that's
                // an expected error.  Unfortunately, it still gets logged
                return;
            }
        }

        // Specify log line format
        $alt_filename = preg_replace('/.*\//', '', $file);
        $logline = sprintf("$typestring: %s: %s (%d): %s", $errstring, $alt_filename, $line, $errmsg);

        // FIXME -- ignore strict errors coming out of CodeIgniter for now.
        if (($errno === E_STRICT) && preg_match('/\/framework\//', $file))
            return;

        // Perform extra goodness in debug mode
        if (getenv('CLEAROS_BOOTSTRAP')) {
            // Append timestamp to log line
            if ($basetime == 0) {
                $basetime = microtime(TRUE);
                $timestamp = 0;
            } else {
                $currenttime = microtime(TRUE);
                $timestamp = microtime(TRUE) - $basetime;
            }

            $logline = sprintf('%.4f: %s', round($timestamp, 4),  $logline);

            // Log messages to standard out when in command-line mode
            if (ini_get('display_errors') && preg_match('/cli/', php_sapi_name()))
                echo "$logline\n";

            // Log messages to custom log file (if set) and standard out on
            if (ini_get('error_log')) {
                date_default_timezone_set('EST');
                $timestamp = date('M j G:i:s T Y');
                error_log("{$timestamp}: $logline\n", 3, ini_get('error_log'));

                foreach ($error->get_trace() as $traceinfo) {
                    if (isset($traceinfo['file'])) {
                        // Backtrace log format
                        $alt_filename = preg_replace('/.*\//', '', $traceinfo['file']);
                        $logline = sprintf(
                            "$typestring: debug backtrace: %s (%d): %s",
                            $alt_filename,
                            $traceinfo['line'],
                            $traceinfo['function']
                        );
                        error_log("{$timestamp}: $logline\n", 3, ini_get('error_log'));
                    }
                }
            }
        } else {
            // Log errors to syslog
            openlog('engine', LOG_NDELAY, LOG_LOCAL6);
            syslog(LOG_INFO, $logline);

            // Log backtrace
            foreach ($error->get_trace() as $traceinfo) {
                // Backtrace log format
                $logline = sprintf(
                    "$typestring: debug backtrace: %s (%d): %s",
                    preg_replace('/.*\//', '', $traceinfo['file']),
                    $traceinfo['line'],
                    $traceinfo['function']
                );
                syslog(LOG_INFO, $logline);
            }

            closelog();
        }
    }

    /**
     * Logs an exception.
     *
     * @param Exception $exception exception object
     *
     * @return void
     */

    public static function log_exception(Exception $exception)
    {
        Logger::log(
            new Error(
                $exception->getCode(),
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine(),
                '',
                Error::TYPE_EXCEPTION,
                $exception->getTrace()
            )
        );
    }

    /**
     * Logs to syslog.
     *
     * @param string $tag     prefix for log message
     * @param string $message short and informative message
     *
     * @return void
     */

    public static function syslog($tag, $message)
    {
        openlog($tag, LOG_NDELAY, LOG_LOCAL6);
        syslog(LOG_INFO, $message);
        closelog();
    }

    /**
     * Logs profiling information.
     *
     * @param string $tag     prefix for log message
     * @param string $line    line number
     * @param string $message short and informative message
     *
     * @return void
     */

    public static function profile($tag, $line, $message = 'called')
    {
        $error = new Error(Error::CODE_DEBUG, $message, $tag, $line, NULL, Error::TYPE_PROFILE);
        Logger::log($error);
    }

    /**
     * Logs profiling information inside the framework.
     *
     * To keep the logging inside the framework code consistent with
     * CodeIgniter, a different profiling method is used.
     *
     * @param string $tag     prefix for log message
     * @param string $line    line number
     * @param string $message short and informative message
     *
     * @return void
     */

    public static function profile_framework($tag, $line, $message = '')
    {
        // Strip MY_ prefix 
        $tag = preg_replace('/^MY_/', '', $tag);

        // Create log format: tag(line)
        $tagline = "$tag($line)";

        // Prefix optional message
        $full_message = (empty($message)) ? $tagline : "$message - $tagline";

        $error = new Error(Error::CODE_DEBUG, $full_message, 'clearos\framework\Core', '0', NULL, Error::TYPE_PROFILE);
        Logger::log($error);
    }

    /**
     * Logs deprecated method calls.
     *
     * @param string $tag     prefix for log message (usually the method name)
     * @param string $line    line number
     * @param string $message short and informative message
     *
     * @return void
     */

    public static function deprecated($tag, $line, $message = 'deprecated method called')
    {
        $error = new Error(Error::CODE_WARNING, $message, $tag, $line, NULL, Error::TYPE_PROFILE);
        Logger::log($error);
    }

}

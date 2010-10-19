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
 * ClearOS error class.
 *
 * @package Framework
 * @author {@link http://www.foundation.com/ ClearFoundation}
 * @license http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @copyright Copyright 2006, 2010 ClearFoundation
 */

/**
 * ClearOS error class.
 *
 * @package Framework
 * @author {@link http://www.foundation.com/ ClearFoundation}
 * @license http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @copyright Copyright 2006, 2010 ClearFoundation
 */

class ClearOsError
{
	protected $code;
	protected $message;
	protected $tag;
	protected $line;
	protected $context;
	protected $caught;
	protected $trace;
	protected $type;

	// Types of error
	//----------------

	const TYPE_EXCEPTION = 11;
	const TYPE_ERROR = 22;
	const TYPE_PROFILE = 33;

	protected $type_map = array(
		ClearOsError::TYPE_EXCEPTION => 'exception',
		ClearOsError::TYPE_ERROR => 'error',
		ClearOsError::TYPE_PROFILE => 'profile' 
	);

	// ClearOsError codes
	//------------

	// Define error code levels to make logging errors consistent across
	// PHP uncaught errors, ClearOS defined errors, and CodeIgniter errors.
	// 
	// Uncaught errors in PHP generate one of the following error codes:
	//    1 => error
	//    2 => warning
	//    4 => parse error
	//    8 => notice
	//   16 => core error
	//   32 => core warning
	//   64 => compile error
	//  128 => compile warning
	//  256 => user error
	//  512 => user warning
	// 1024 => user notice
	// 2048 => strict
	//
	// CodeIgniter defines 3 levels (which get mapped to the CODE_X tags here)
	//    1 => ERROR
	//    2 => DEBUG
	//    3 => INFO
	//---------------------------------------------------------------

	const CODE_ERROR = -1;
	const CODE_WARNING = -2;
	const CODE_INFO = -4;
	const CODE_DEBUG = -8;

	protected $code_map = array(
		ClearOsError::CODE_ERROR => 'error',
		ClearOsError::CODE_WARNING => 'warning',
		ClearOsError::CODE_INFO => 'info',
		ClearOsError::CODE_DEBUG => 'debug',
		E_STRICT => 'PHP strict',   
		E_ERROR => 'PHP error',
		E_WARNING => 'PHP warning',
		E_PARSE => 'PHP parse error',
		E_NOTICE => 'PHP notice',
		E_CORE_ERROR => 'PHP core error',
		E_CORE_WARNING => 'PHP core warning',
		E_COMPILE_ERROR => 'PHP compile error',
		E_COMPILE_WARNING => 'PHP compile warning',
		E_USER_ERROR => 'PHP user error',
		E_USER_WARNING => 'PHP user warning',
		E_USER_NOTICE => 'PHP user notice'
	);

	/**
	 * ClearOsError constructor.
	 *
	 * @param integer $code error code
	 * @param string $message error message
	 * @param string $tag a method name or some other nickname
	 * @param integer $line line number
	 * @param array $context error context
	 * @param integer $type type of error - exception, error, trace
	 * @param boolean $caught true if error was caught by application
	 * @param array $trace error back trace
	 * @returns void
	 */
	function __construct($code, $message, $tag, $line, $context = null, $type, $caught = true, $trace = null)
	{
		$this->code = $code;
		$this->message = $message;
		$this->tag = $tag;
		$this->line = $line;
		$this->context = $context;
		$this->type = $type;
		$this->caught = $caught;
		$this->trace = $trace;
	}

	/**
	 * Returns error code.
	 *
	 * @returns integer error code
	 */
	function GetCode()
	{
		return $this->code;
	}

	/**
	 * Returns error code string.
	 *
	 * @returns string error code string
	 */
	function GetCodeString()
	{
		if (isset($this->code_map[$this->code]))
			return $this->code_map[$this->code];
		else
			return "unknown";
	}

	/**
	 * Returns error message.
	 *
	 * @returns string error message
	 */
	function GetMessage()
	{
		return $this->message;
	}

	/**
	 * Returns error tag.
	 *
	 * @returns string error tag
	 */
	function GetTag()
	{
		return $this->tag;
	}

	/**
	 * Returns line number where error occurred.
	 *
	 * @returns integer line number
	 */
	function GetLine()
	{
		return $this->line;
	}

	/**
	 * Returns error context.
	 *
	 * @returns array error context
	 */
	function GetContext()
	{
		return $this->context;
	}

	/**
	 * Returns flag on state of the error.
	 *
	 * @returns boolean true if error was caught by application.
	 */
	function IsCaught()
	{
		return $this->caught;
	}

	/**
	 * Returns error type: error, exception or profile.
	 *
	 * @returns string error type
	 */
	function GetType()
	{
		return $this->type;
	}

	/**
	 * Returns error type string: error, exception or profile.
	 *
	 * @returns string error type
	 */
	function GetTypeString()
	{
		if (isset($this->type_map[$this->type]))
			return $this->type_map[$this->type];
		else
			return "unknown";
	}

	/**
	 * Returns error trace.
	 *
	 * @returns array error trace.
	 */
	function GetTrace()
	{
		if ($this->trace)
			return $this->trace;
		else
			return array();
	}
}

// vim: syntax=php ts=4
?>

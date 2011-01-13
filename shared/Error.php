<?php

/**
 * ClearOS error class.
 *
 * @category  ClearOS
 * @package   Framework
 * @author    ClearFoundation <developer@clearfoundation.com>
 * @copyright 2006-2011 ClearFoundation
 * @license   http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link      http://www.clearfoundation.com/docs/developer/apps/fraemwork/
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
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * ClearOS error class.
 *
 * Define error code levels to make logging errors consistent across
 * PHP uncaught errors, ClearOS defined errors, and CodeIgniter errors.
 * 
 * Uncaught errors in PHP generate one of the following error codes:
 *    1 => error
 *    2 => warning
 *    4 => parse error
 *    8 => notice
 *   16 => core error
 *   32 => core warning
 *   64 => compile error
 *  128 => compile warning
 *  256 => user error
 *  512 => user warning
 * 1024 => user notice
 * 2048 => strict
 *
 * CodeIgniter defines 3 levels (which get mapped to the CODE_X tags here)
 *    1 => ERROR
 *    2 => DEBUG
 *    3 => INFO
 *
 * @category  ClearOS
 * @package   Framework
 * @author    ClearFoundation <developer@clearfoundation.com>
 * @copyright 2006-2011 ClearFoundation
 * @license   http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link      http://www.clearfoundation.com/docs/developer/apps/framework/
 */

class Error
{
    protected $code;
    protected $message;
    protected $tag;
    protected $line;
    protected $context;
    protected $caught;
    protected $trace;
    protected $type;

    const CODE_ERROR = -1;
    const CODE_WARNING = -2;
    const CODE_INFO = -4;
    const CODE_DEBUG = -8;
    const TYPE_EXCEPTION = 11;
    const TYPE_ERROR = 22;
    const TYPE_PROFILE = 33;

    protected $type_map = array(
        Error::TYPE_EXCEPTION => 'exception',
        Error::TYPE_ERROR => 'error',
        Error::TYPE_PROFILE => 'profile' 
    );

    protected $code_map = array(
        Error::CODE_ERROR => 'error',
        Error::CODE_WARNING => 'warning',
        Error::CODE_INFO => 'info',
        Error::CODE_DEBUG => 'debug',
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
     * Error constructor.
     *
     * @param integer $code    error code
     * @param string  $message error message
     * @param string  $tag     a method name or some other nickname
     * @param integer $line    line number
     * @param array   $context error context
     * @param integer $type    type of error - exception, error, profile
     * @param boolean $caught  TRUE if error was caught by application
     * @param array   $trace   error back trace
     *
     * @return void
     */

    public function __construct($code, $message, $tag, $line, $context, $type, $caught = TRUE, $trace = NULL)
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
     * @return integer error code
     */

    public function get_code()
    {
        return $this->code;
    }

    /**
     * Returns error code string.
     *
     * @return string error code string
     */

    public function get_code_string()
    {
        if (isset($this->code_map[$this->code]))
            return $this->code_map[$this->code];
        else
            return "unknown";
    }

    /**
     * Returns error context.
     *
     * @return array error context
     */

    public function get_context()
    {
        return $this->context;
    }

    /**
     * Returns line number where error occurred.
     *
     * @return integer line number
     */

    public function get_line()
    {
        return $this->line;
    }

    /**
     * Returns error message.
     *
     * @return string error message
     */

    public function get_message()
    {
        return $this->message;
    }

    /**
     * Returns error tag.
     *
     * @return string error tag
     */

    public function get_tag()
    {
        return $this->tag;
    }

    /**
     * Returns error trace.
     *
     * @return array error trace.
     */

    public function get_trace()
    {
        if ($this->trace)
            return $this->trace;
        else
            return array();
    }

    /**
     * Returns error type: error, exception or profile.
     *
     * @return string error type
     */

    public function get_type()
    {
        return $this->type;
    }

    /**
     * Returns error type string: error, exception or profile.
     *
     * @return string error type
     */

    public function get_type_string()
    {
        if (isset($this->type_map[$this->type]))
            return $this->type_map[$this->type];
        else
            return "unknown";
    }

    /**
     * Returns flag on state of the error.
     *
     * @return boolean TRUE if error was caught by application.
     */

    public function is_caught()
    {
        return $this->caught;
    }
}

?>

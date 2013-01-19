<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ClearOS form validation override class.
 *
 * @category   Framework
 * @package    Base
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2013 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/framework/
 */

/**
 * ClearOS form validation override class.
 *
 * @category   Framework
 * @package    Base
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2013 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/framework/
 */

class MY_Form_validation extends CI_Form_validation {

    // ClearFoundation -- remove paragraph tag
    protected $_error_prefix        = '';
    protected $_error_suffix        = '';

    /**
     * Constructor
     */

    public function __construct($rules = array())
    {
        parent::__construct($rules);
    }

    /**
     * Sets a form validation policy.
     *
     * Override type: new method.
     *
     * Similar to set_rule, but provides a gateway into the ClearOS validators
     * in classes.
     *
     * @param string  $field           field name
     * @param string  $library         library that contains validator
     * @param string  $method          method name of the validator
     * @param boolean $is_required     set to TRUE if field is required
     * @param boolean $check_existence set to TRUE if duplicates are not allowed
     */ 

    function set_policy($field, $library, $method, $is_required = FALSE, $check_existence = FALSE)
    {
        // Prefix rule to make it possible to identify it in execute() method
        $rule = 'clearos.' . $library . '.' . $method . '.' . $check_existence;

        // Add required hook
        if ($is_required)
            $rule = 'required|' . $rule;

        $this->set_rules($field, '', $rule);
    }

    /**
     * Sets error field.
     *
     * Override type: new method.
     *
     * In some circumstances, it may be necessary to validate multiple fields at
     * once.  For example, a port range needs to make sure the "low" port is
     * higher than the "high" port.  The app developer can do these extra
     * checks and run this set_error() method when appropriate.
     *
     * @param string $field field name
     * @param string $error error message
     */

    function set_error($field, $error)
    {
        $this->_field_data[$field]['error'] = $error;
    }

    /**
     * Executes the validation routines.
     *
     * Override type: changed method
     *
     * This method was changed to support the validation routines in ClearOS
     * classes, i.e. when set_policy() is used.
     *
     * @access private
     * @param array   $row      row
     * @param array   $rules    rules
     * @param mixed   $postdata post data
     * @param integer $cycles   cycles
     * @return mixed
     */

    protected function _execute($row, $rules, $postdata = NULL, $cycles = 0)
    {
        // If the $_POST data is an array we will run a recursive call
        if (is_array($postdata))
        {
            foreach ($postdata as $key => $val)
            {
                $this->_execute($row, $rules, $val, $cycles);
                $cycles++;
            }

            return;
        }

        // --------------------------------------------------------------------

        // If the field is blank, but NOT required, no further tests are necessary
        $callback = FALSE;
        if ( ! in_array('required', $rules) AND is_null($postdata))
        {
            // Before we bail out, does the rule contain a callback?
            // ClearFoundation add ClearOS callback
            if (preg_match("/(callback_\w+(\[.*?\])?)/", implode(' ', $rules), $match))
            {
                $callback = TRUE;
                $rules = (array('1' => $match[1]));
            }
            else
            {
                return;
            }
        }

        // --------------------------------------------------------------------

        // Isset Test. Typically this rule will only apply to checkboxes.
        if (is_null($postdata) AND $callback == FALSE)
        {
            if (in_array('isset', $rules, TRUE) OR in_array('required', $rules))
            {
                // Set the message type
                $type = (in_array('required', $rules)) ? 'required' : 'isset';

                if ( ! isset($this->_error_messages[$type]))
                {
                    if (FALSE === ($line = $this->CI->lang->line($type)))
                    {
                        $line = 'The field was not set';
                    }
                }
                else
                {
                    $line = $this->_error_messages[$type];
                }

                // Build the error message
                $message = sprintf($line, $this->_translate_fieldname($row['label']));

                // Save the error message
                $this->_field_data[$row['field']]['error'] = $message;

                if ( ! isset($this->_error_array[$row['field']]))
                {
                    $this->_error_array[$row['field']] = $message;
                }
            }

            return;
        }

        // --------------------------------------------------------------------

        // Cycle through each rule and run it
        foreach ($rules As $rule)
        {
            // ClearFoundation - add callback type
            $callback_type = '';

            $_in_array = FALSE;

            // We set the $postdata variable with the current data in our master array so that
            // each cycle of the loop is dealing with the processed data from the last cycle
            if ($row['is_array'] == TRUE AND is_array($this->_field_data[$row['field']]['postdata']))
            {
                // We shouldn't need this safety, but just in case there isn't an array index
                // associated with this cycle we'll bail out
                if ( ! isset($this->_field_data[$row['field']]['postdata'][$cycles]))
                {
                    continue;
                }

                $postdata = $this->_field_data[$row['field']]['postdata'][$cycles];
                $_in_array = TRUE;
            }
            else
            {
                $postdata = $this->_field_data[$row['field']]['postdata'];
            }

            // 
            // Is the rule a callback?
            // ClearFoundation add ClearOS callback
            $callback = FALSE;
            if (substr($rule, 0, 9) == 'callback_')
            {
                $rule = substr($rule, 9);
                $callback = TRUE;
                $callback_type = 'normal';
            }
            else if (substr($rule, 0, 8) == 'clearos.')
            {
                $rule = substr($rule, 8);
                $callback = TRUE;
                $callback_type = 'clearos';
            }

            // Strip the parameter (if exists) from the rule
            // Rules can contain a parameter: max_length[5]
            $param = FALSE;
            if (preg_match("/(.*?)\[(.*)\]/", $rule, $match))
            {
                $rule    = $match[1];
                $param    = $match[2];
            }

            // Call the function that corresponds to the rule
            if ($callback === TRUE)
            {
                // ClearFoundation - add ClearOS callback
                if ($callback_type === 'clearos')
                {
                    if (empty($postdata) && ( ! in_array('required', $rules, TRUE) ))
                        continue;

                    // Empty rule -- used in special cases 
                    if (($rule === '.') || ($rule === '..'))
                        continue;

                    $matches = array();

                    if (!preg_match("/([^\.]+)\.(\w+)\.(.*)/", $rule, $matches)) {
                        show_error("Ooops.  The validation rule is borked -  $rule");
                        continue;
                    }

                    // The standard CI loader is not namespace aware, so 
                    // using clearos_load_library is required.

                    $clear_library = $matches[1];
                    $clear_method = $matches[2];
                    $clear_check_exists = $matches[3];

                    $clear_class_name = preg_replace('/\//', '\\', $clear_library);
                    $clear_class_name = "\\clearos\\apps\\$clear_class_name";

                    clearos_load_library($clear_library);

                    $clear_object = new $clear_class_name;

                    if ((bool)$clear_check_exists)
                        $error_message = $clear_object->$clear_method($postdata, (bool)$clear_check_exists);
                    else
                        $error_message = $clear_object->$clear_method($postdata);

                    $result = ($error_message) ? FALSE : TRUE;    

                    $this->CI->form_validation->set_message($rule, $error_message);
                } 
                else
                {
                    if ( ! method_exists($this->CI, $rule))
                    {
                        continue;
                    }

                    // Run the function and grab the result
                    $result = $this->CI->$rule($postdata, $param);
                }

                // Re-assign the result to the master data array
                if ($_in_array == TRUE)
                {
                    $this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
                }
                else
                {
                    $this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
                }

                // If the field isn't required and we just processed a callback we'll move on...
                if ( ! in_array('required', $rules, TRUE) AND $result !== FALSE)
                {
                    continue;
                }
            }
            else
            {
                if ( ! method_exists($this, $rule))
                {
                    // If our own wrapper function doesn't exist we see if a native PHP function does.
                    // Users can use any native PHP function call that has one param.
                    if (function_exists($rule))
                    {
                        $result = $rule($postdata);

                        if ($_in_array == TRUE)
                        {
                            $this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
                        }
                        else
                        {
                            $this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
                        }
                    }
                    else
                    {
                        log_message('debug', "Unable to find validation rule: ".$rule);
                    }

                    continue;
                }

                $result = $this->$rule($postdata, $param);

                if ($_in_array == TRUE)
                {
                    $this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
                }
                else
                {
                    $this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
                }
            }

            // Did the rule test negatively?  If so, grab the error.
            if ($result === FALSE)
            {
                if ( ! isset($this->_error_messages[$rule]))
                {
                    if (FALSE === ($line = $this->CI->lang->line($rule)))
                    {
                        $line = 'Unable to access an error message corresponding to your field name.';
                    }
                }
                else
                {
                    $line = $this->_error_messages[$rule];
                }

                // Is the parameter we are inserting into the error message the name
                // of another field?  If so we need to grab its "field label"
                if (isset($this->_field_data[$param]) AND isset($this->_field_data[$param]['label']))
                {
                    $param = $this->_translate_fieldname($this->_field_data[$param]['label']);
                }

                // Build the error message
                $message = sprintf($line, $this->_translate_fieldname($row['label']), $param);

                // Save the error message
                $this->_field_data[$row['field']]['error'] = $message;

                if ( ! isset($this->_error_array[$row['field']]))
                {
                    $this->_error_array[$row['field']] = $message;
                }

                return;
            }
        }
    }
}

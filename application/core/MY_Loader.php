<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ClearOS loader class override.
 *
 * @category   Framework
 * @package    Base
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2013 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/framework/
 */

require APPPATH."third_party/MX/Loader.php";

/**
 * ClearOS loader class override.
 *
 * @category   Framework
 * @package    Base
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2013 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/framework/
 */

class MY_Loader extends MX_Loader {

    function __construct()
    {
        parent::__construct();
    }

    /**
     * ClearOS factory class loader.
     *
     * Override type: new method
     *
     * ClearOS uses factories for implementing drivers.  This is a new method
     * for the Loader class.
     *
     * @param string $factory     the name of the factory
     * @param mixed  $parms       the optional parameters
     * @param string $object_name an optional object name
     *
     * @return void
     */

    public function factory($factory = '', $params = NULL, $object_name = NULL)
    {
        // Generate object name if not specified
        if ($object_name === NULL) {
            $object_name = preg_replace('/.*\//', '', $factory);
            $object_name = preg_replace('/_Factory$/', '', $object_name);
        }

        // Use two underscores just to avoid name conflicts
        $factory_object = strtolower($object_name . '__factory');

        // Find the name of the driver
        $this->library($factory, NULL, $factory_object);
        $driver = $this->$factory_object->framework_create();

        // Load the library
        $this->library($driver, $params, $object_name);
    }

    /**
     * ClearOS library loader override.
     *
     * Override type: changed method
     *
     * Added namespace support
     *
     * @param string the name of the class
     * @param mixed  the optional parameters
     * @param string an optional object name
     *
     * @return void
     */

    public function library($library = '', $params = NULL, $object_name = NULL) {

        if (is_array($library)) return $this->libraries($library);

        $class = strtolower(basename($library));

        if (isset($this->_ci_classes[$class]) AND $_alias = $this->_ci_classes[$class])
            return CI::$APP->$_alias;

        ($_alias = strtolower($object_name)) OR $_alias = $class;

        list($path, $_library) = Modules::find($library, $this->_module, 'libraries/');

        /* load library config file as params */
        if ($params == NULL) {
            list($path2, $file) = Modules::find($_alias, $this->_module, 'config/');
            ($path2) AND $params = Modules::load_file($file, $path2, 'config');
        }

        if ($path === FALSE) {

            $this->_ci_load_class($library, $params, $object_name);
            $_alias = $this->_ci_classes[$class];

        } else {

            Modules::load_file($_library, $path);

            $library = ucfirst($_library);

            // ClearFoundation -- add namespace
            $path = preg_replace('/\/\//', '/', $path); // Remove double slashes

            $namespace = preg_replace('/\/trunk/', '', $path);
            $namespace = preg_replace('/\/libraries\/$/', '', $namespace);
            $namespace = preg_replace('/.*\//', '', $namespace);
            $library = '\clearos\\apps\\' . $namespace . '\\' . $library;
            // ClearFoundation -- end

            CI::$APP->$_alias = new $library($params);

            $this->_ci_classes[$class] = $_alias;
        }

        return CI::$APP->$_alias;
    }

    /** Load a module controller **/
    public function module($module, $params = NULL) {

        if (is_array($module)) return $this->modules($module);

        $_alias = strtolower(basename($module));
        CI::$APP->$_alias = Modules::load(array($module => $params));
        return CI::$APP->$_alias;
    }
}

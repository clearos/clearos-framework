<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ClearOS router class override.
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
require_once $bootstrap . '/bootstrap.php';

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

use \clearos\framework\Config as ClearOsConfig;

require APPPATH."third_party/MX/Router.php";

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * ClearOS router class override.
 *
 * @category   Framework
 * @package    Base
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2013 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/framework/
 */

class MY_Router extends MX_Router
{
    /**
     * ClearOS locater override.
     *
     * Override type: changed method
     *
     * Added support for various SCMs.
     *
     * @param array $segments segments
     *
     * @return void
     */

    public function locate($segments) {        
        
        $this->module = '';
        $this->directory = '';
        $ext = $this->config->item('controller_suffix').EXT;
        
        /* use module route if available */
        if (isset($segments[0]) AND $routes = Modules::parse_routes($segments[0], implode('/', $segments))) {
            $segments = $routes;
        }
    
        /* get the segments array elements */
        list($module, $directory, $controller) = array_pad($segments, 3, NULL);

        /* check modules */
        foreach (Modules::$locations as $location => $offset) {

            // ClearFoundation -- add support for multiple development trees
            foreach (ClearOSConfig::$scm_subpaths as $scm_subpath) {        
                /* module exists? */
                if (is_dir($source = $location.$module.'/'.$scm_subpath.'/controllers/')) {
                    
                    $this->module = $module;
                    $this->directory = $offset.$module.'/'.$scm_subpath.'/controllers/';
                    
                    /* module sub-controller exists? */
                    if($directory AND is_file($source.$directory.$ext)) {
                        return array_slice($segments, 1);
                    }
                        
                    /* module sub-directory exists? */
                    if($directory AND is_dir($source.$directory.'/')) {

                        $source = $source.$directory.'/'; 
                        $this->directory .= $directory.'/';

                        /* module sub-directory controller exists? */
                        if(is_file($source.$directory.$ext)) {
                            return array_slice($segments, 1);
                        }
                    
                        /* module sub-directory sub-controller exists? */
                        if($controller AND is_file($source.$controller.$ext))    {
                            return array_slice($segments, 2);
                        }
                    }
                    
                    /* module controller exists? */            
                    if(is_file($source.$module.$ext)) {
                        return $segments;
                    }
                }
            }
        }
        
        /* application controller exists? */            
        if (is_file(APPPATH.'controllers/'.$module.$ext)) {
            return $segments;
        }
        
        /* application sub-directory controller exists? */
        if($directory AND is_file(APPPATH.'controllers/'.$module.'/'.$directory.$ext)) {
            $this->directory = $module.'/';
            return array_slice($segments, 1);
        }
        
        /* application sub-directory default controller exists? */
        if (is_file(APPPATH.'controllers/'.$module.'/'.$this->default_controller.$ext)) {
            $this->directory = $module.'/';
            return array($this->default_controller);
        }
    }
}

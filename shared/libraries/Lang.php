<?php

/**
 * ClearOS language handling class. 
 *
 * @category   Framework
 * @package    Shared
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @author     EllisLab Inc <info@ellislab.com>
 * @copyright  2011 ClearFoundation
 * @copyright  2008-2010 EllisLab, Inc
 * @license    http://codeigniter.com/user_guide/license.html CodeIgniter
 * @link       http://www.clearfoundation.com/docs/developer/framework/
 */

///////////////////////////////////////////////////////////////////////////////
// N A M E S P A C E
///////////////////////////////////////////////////////////////////////////////

namespace clearos\framework;

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

use \clearos\framework\Config as Config;

require_once 'Config.php';

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * ClearOS language handling class. 
 *
 * @category   Framework
 * @package    Shared
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @author     EllisLab Inc <info@ellislab.com>
 * @copyright  2011 ClearFoundation
 * @copyright  2008-2010 EllisLab, Inc
 * @license    http://codeigniter.com/user_guide/license.html CodeIgniter
 * @link       http://www.clearfoundation.com/docs/developer/framework/
 */

class Lang
{
    var $language = array();
    var $is_loaded = array();

    /**
     * Loads a language file.
     *
     * @param string $target application language target
     *
     * @return TRUE if load was successful
     */

    public function load($target = '')
    {
        if (in_array($target, $this->is_loaded, TRUE))
            return;

        // Support short form as well as full path
        // - load('date') -- which is equivalent to load('date/date')
        // - load('base/daemon')

        if (preg_match('/\//', $target)) {
            list($app, $langfile) = preg_split('/\//', $target, 2);
        } else {
            $app = $target;
            $langfile = $target;
        }

        $langfile .= '_lang.php';

        // Grab the development version 
        if (!empty(Config::$clearos_devel_versions['app'][$app]))
            $version = Config::$clearos_devel_versions['app'][$app];
        else if (!empty(Config::$clearos_devel_versions['app']['default']))
            $version = Config::$clearos_devel_versions['app']['default'];
        else
            $version = '';

        // FIXME - pull in language
        // $deft_lang = ( ! isset($config['language'])) ? 'english' : $config['language'];
        // $idiom = ($deft_lang == '') ? 'english' : $deft_lang;
        $language = 'en_US';

        // Load the language file
        $langpath = Config::$apps_path . '/' . $app . '/' . $version . "/language/$language/$langfile";

        if (file_exists($langpath)) {
            include "$langpath";
        } else {
            // FIXME?
        }

        $this->is_loaded[] = $target;
        $this->language = array_merge($this->language, $lang);

        unset($lang);
    }

    /**
     * Fetch a single line of text from the language array
     *
     * @param string $line language line
     *
     * @return string translated string
     */

    public function line($line = '')
    {
        $line = ($line == '' OR ! isset($this->language[$line])) ? FALSE : $this->language[$line];

        return $line;
    }
}

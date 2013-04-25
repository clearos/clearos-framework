<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ClearOS language class override.
 *
 * @category   Framework
 * @package    Base
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2013 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/framework/
 */

require APPPATH."third_party/MX/Lang.php";

/**
 * ClearOS language class override.
 *
 * @category   Framework
 * @package    Base
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2013 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/framework/
 */

class MY_Lang extends MX_Lang {

    function __construct()
    {
        parent::__construct();
    }

    /**
     * ClearOS language loader override.
     *
     * Override type: changed method
     *
     * Added support for caching, Pootle and translators
     *
     * @param array   $langfile   lang files
     * @param string  $lang       language
     * @param boolean $return a   return value
     * @param boolean $add suffix add suffix flag
     * @param string  $alt_path   alternate path
     * @param string  $module     module
     *
     * @return array language
     */

    public function load($langfile = array(), $lang = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '', $_module = '')    {
        
        if (is_array($langfile)) {
            foreach($langfile as $_lang) $this->load($_lang);
            return $this->language;
        }
            
        // ClearFoundation 
        // use /etc/sysconfig/i18n which is cached in PHP format to keep things snappy.
        if (CI::$APP->session->userdata('lang_code')) {
            $idiom = CI::$APP->session->userdata('lang_code');
        } else if (file_exists(CLEAROS_TEMP_DIR . '/language_cache.php')) {
            include CLEAROS_TEMP_DIR . '/language_cache.php';
            $idiom = $language;
        } else {
            $deft_lang = CI::$APP->config->item('language');
            $idiom = ($lang == '') ? $deft_lang : $lang;
        }
    
        // ClearFoundation
        // - fall back to en_US if translation is unavailable
        // - add helper for translators
        //
        // In devel mode, we tack on the en_US translations to $translations.
        // This is used in system/core/Lang.php to see if the translation exists.

        $langfile = "$langfile/$langfile";

        if (in_array($langfile.'_lang'.EXT, $this->is_loaded, TRUE))
            return $this->language;

        $_module OR $_module = CI::$APP->router->fetch_module();

        list($path, $_langfile) = Modules::find($langfile.'_lang', $_module, 'language/'.$idiom.'/');

        if ($path === FALSE) {
            list($path, $_langfile) = Modules::find($langfile.'_lang', $_module, 'language/en_US/');
        }
        
        if ($path === FALSE) {
            // ClearFoundation - just log this
            // if ($lang = parent::load($langfile, $lang, $return, $add_suffix, $alt_path)) return $lang;
        } else {

            if($lang = Modules::load_file($_langfile, $path, 'lang')) {
                // KLUDGE: add special language mapping file
                if ($_langfile == 'base_lang') {
                    $include_file = clearos_app_base('base') . '/language/en_US/base_framework_lang.php';
                    if (file_exists($include_file))
                        include $include_file;
                }

                if ($return) return $lang;

                // Developer mode -- add the "translated" info which holds state of all translated tags
                if (file_exists('/etc/clearos/devel.d/translator_mode')) {
                    $translated_file = '/var/clearos/base/translations/base/trunk/language/' . $idiom . '/translated.php';

                    if ($idiom == 'en_US') {
                        $this->language['is_en_us'] = TRUE;
                    } else if (file_exists($translated_file) && (!isset($this->language['is_translated']))) {
                        include $translated_file;
                        $this->language['is_translated'] = $translated;
                        $this->language['is_en_us'] = FALSE;
                    }
                }

                $this->language = array_merge($this->language, $lang);
                $this->is_loaded[] = $langfile.'_lang'.EXT;
                unset($lang);
            }
        }
        
        return $this->language;
    }

    /**
     * Fetch a single line of text from the language array
     *
     * @param string $line the language line
     *
     * @return string translation
     */

    function line($line = '')
    {
        // ClearFoundation - custom handler for translators
        if (file_exists('/etc/clearos/devel.d/translator_mode')) {
            if (isset($this->language['is_en_us']) && $this->language['is_en_us'])
                $line = $this->language[$line];
            else if (isset($this->language['is_translated'][$line]) && $this->language['is_translated'][$line])
                $line = $this->language[$line];
            else
                $line = '**' .  $line . '**';
        } else if ($line == '' OR ! isset($this->language[$line])) {
            $line = '****' .  $line . '****';
        } else {
            $line = $this->language[$line];
        }

        return $line;
    }
}

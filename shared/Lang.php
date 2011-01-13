<?php

///////////////////////////////////////////////////////////////////////////////
//
// Copyright 2008 - 2010 EllisLab, Inc
// Copyright 2006, 2010 ClearFoundation
//
///////////////////////////////////////////////////////////////////////////////
//
// CodeIgniter license
//
///////////////////////////////////////////////////////////////////////////////

/**
 * ClearOS language handling class. 
 *
 * @package Framework
 * @author ExpressionEngine Dev Team, {@link http://www.clearfoundation.com/ ClearFoundation}
 * @license	http://codeigniter.com/user_guide/license.html CodeIgniter
 * @copyright Copyright 2008 - 2010 EllisLab, Inc., 2010 ClearFoundation
 */

///////////////////////////////////////////////////////////////////////////////
// B O O T S T R A P
///////////////////////////////////////////////////////////////////////////////

$bootstrap = isset($_ENV['CLEAROS_BOOTSTRAP']) ? $_ENV['CLEAROS_BOOTSTRAP'] : '/usr/clearos/framework/shared';
require_once($bootstrap . '/bootstrap.php');

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * ClearOS language handling class. 
 *
 * @package Framework
 * @author ExpressionEngine Dev Team, {@link http://www.clearfoundation.com/ ClearFoundation}
 * @license	http://codeigniter.com/user_guide/license.html CodeIgniter
 * @copyright Copyright 2008 - 2010 EllisLab, Inc., 2010 ClearFoundation
 */

class ClearOsLang {

	var $language = array();
	var $is_loaded = array();

	/**
	 * Constructor
	 *
	 * @access	public
	 */
	function __construct()
	{}

	// --------------------------------------------------------------------

	/**
	 * Loads a language file.
	 *
	 * @param string $langtarget application language target
	 * @return true if load was successful
	 */
	function load($langtarget = '')
	{
		if (in_array($langtarget, $this->is_loaded, TRUE))
			return;

		// Support short form as well as full path
		// - load('date') -- which is equivalent to load('date/date')
		// - load('base/daemon')

		if (preg_match('/\//', $langtarget)) {
			list($app, $langfile) = preg_split('/\//', $langtarget, 2);
		} else {
			$app = $langtarget;
			$langfile = $langtarget;
		}

		$langfile .= '_lang.php';

		// Grab the development version 
		if (!empty(ClearOsConfig::$clearos_devel_versions['app'][$app]))
			$version = ClearOsConfig::$clearos_devel_versions['app'][$app];
		else if (!empty(ClearOsConfig::$clearos_devel_versions['app']['default']))
			$version = ClearOsConfig::$clearos_devel_versions['app']['default'];
		else
			$version = '';

		// FIXME - pull in language
		// $deft_lang = ( ! isset($config['language'])) ? 'english' : $config['language'];
		// $idiom = ($deft_lang == '') ? 'english' : $deft_lang;
		$language = 'en_US';

		// Load the language file
		$langpath = ClearOsConfig::$apps_path . '/' . $app . '/' . $version . "/language/$language/$langfile";

		if (file_exists($langpath)) {
			include($langpath);
		} else {
			// FIXME?
		}

		$this->is_loaded[] = $langtarget;
		$this->language = array_merge($this->language, $lang);

		unset($lang);
	}

	/**
	 * Fetch a single line of text from the language array
	 *
	 * @access	public
	 * @param	string	$line 	the language line
	 * @return	string
	 */
	function line($line = '')
	{
		$line = ($line == '' OR ! isset($this->language[$line])) ? FALSE : $this->language[$line];
		return $line;
	}
}

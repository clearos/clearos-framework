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
 * @author ExpressionEngine Dev Team, {@link http://www.foundation.com/ ClearFoundation}
 * @license	http://codeigniter.com/user_guide/license.html CodeIgniter
 * @copyright Copyright 2008 - 2010 EllisLab, Inc., 2010 ClearFoundation
 */

/**
 * ClearOS language handling class. 
 *
 * @package Framework
 * @author ExpressionEngine Dev Team, {@link http://www.foundation.com/ ClearFoundation}
 * @license	http://codeigniter.com/user_guide/license.html CodeIgniter
 * @copyright Copyright 2008 - 2010 EllisLab, Inc., 2010 ClearFoundation
 */

class ClearOsLang {

	var $use_ci = TRUE;
	var $language = array();
	var $is_loaded = array();

	/**
	 * Constructor
	 *
	 * @access	public
	 */
	function ClearOsLang()
	{}

	// --------------------------------------------------------------------

	/**
	 * Loads a language file.
	 *
	 * @param string $langfile language file
	 * @return true if load was successful
	 */
	function load($langfile = '')
	{
		$langfile = $langfile . "_lang.php";

		if (in_array($langfile, $this->is_loaded, TRUE))
			return;

		// FIXME - pull in language
		// $deft_lang = ( ! isset($config['language'])) ? 'english' : $config['language'];
		// $idiom = ($deft_lang == '') ? 'english' : $deft_lang;

		// Load the language file
//		if (file_exists(APPPATH.'language/'.$idiom.'/'.$langfile)) {
	//		include(APPPATH.'language/'.$idiom.'/'.$langfile);
			include("/home/peter/devel/clearos/apps/date/trunk/language/english/$langfile");
//		} else {
			// FIXME
//		}

		$this->is_loaded[] = $langfile;
		$this->language = array_merge($this->language, $lang);

		unset($lang);
	}

	// --------------------------------------------------------------------

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

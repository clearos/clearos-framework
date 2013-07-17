<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ClearOS security override class.
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
 * ClearOS security override class.
 *
 * @category   Framework
 * @package    Base
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2013 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/framework/
 */

class MY_Security extends CI_Security {

    /**
     * Constructor
     */

    public function __construct()
    {
        parent::__construct();
    }

	/**
	 * Set Cross Site Request Forgery Protection Cookie
	 *
	 * @access	public
	 * @return	null
	 */
	public function csrf_set_cookie()
	{
		$expire = time() + $this->_csrf_expire;

        // If CSRF expire is set to 0, cookie should expire with session
        if ($this->_csrf_expire == 0)
            $expire = 0;

		$secure_cookie = (config_item('cookie_secure') === TRUE) ? 1 : 0;

		if ($secure_cookie && (empty($_SERVER['HTTPS']) OR strtolower($_SERVER['HTTPS']) === 'off'))
		{
			return FALSE;
		}

		setcookie($this->_csrf_cookie_name, $this->_csrf_hash, $expire, config_item('cookie_path'), config_item('cookie_domain'), $secure_cookie);

		log_message('debug', "CRSF cookie Set");

		return $this;
	}
}

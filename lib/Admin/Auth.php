<?php
/**
 * Project pop-lin.com
 *
 * @author      Mehmet Ali Ergut <mim@taximact.com>
 * @copyright   Mehmet Ali Ergut
 * @version     0.1
 * @date        01/02/14 03:41
 * @package     03:41
 */

namespace Admin;
use Config\DB;

/**
 * Class Auth
 *
 * @package Admin
 */
class Auth
{
	private $db;

	function __construct(DB $db)
	{
		$this->db = $db;
	}
	/**
	 * @param array $login_params
	 *
	 * @return bool
	 */
	public function checkAuth($login_params=array())
	{
		$login_params['password'] = $this->makeHash($login_params);
		$user = new Users($this->db);
		$checkResult = $user->checkLogin($login_params);
		if($checkResult)
		{
			$this->createSession($checkResult);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @return bool
	 */
	public function control()
	{
		if(isset($_SESSION['hash']))
		{
			if(intval($_SESSION['id']) < 1)
				return false;

			$user = new Users($this->db);
			$userdata = $user->get($_SESSION['id']);
			$password = $this->decrypt($userdata->email,$_SESSION['hash']);
			return ($userdata->password == $password)?true:false;
		} else {
			$this->logout();
			return false;
		}
	}

	/**
	 * @return bool
	 */
	public function logout()
	{
		$_SESSION['hash'] = '';
		$_SESSION['id'] = '';
		$_SESSION['level'] = '';
		session_unset();
		return true;
	}

	/**
	 * @param array $data
	 *
	 * @return string
	 */
	public function makeHash($data=array())
	{
		try {
			$key = $data['username'];
			$text = $data['password'];
			$iv = substr(md5($key), 0, mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_ECB));
			$encrypted = bin2hex(mcrypt_encrypt(MCRYPT_CAST_256, $key, $text, "ecb", $iv));
			return $encrypted;
		} catch (\Exception $e)
		{
			return false;
		}
	}

	/**
	 * @param $key
	 * @param $encrypted
	 *
	 * @return string
	 */
	public function decrypt($key, $encrypted)  {
		$iv = substr(md5($key), 0, mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_ECB));
		$binary_encrypted = pack("H" . strlen($encrypted), $encrypted);
		$text = mcrypt_decrypt(MCRYPT_CAST_256, $key, $binary_encrypted, "ecb", $iv);
		return trim(chop($text));
	}

	/**
	 * @param mixed $params
	 *
	 * @return bool
	 */
	function createSession($params)
	{
		$users = new Users($this->db);
		$user = $users->get($params['id']);

		$_SESSION['hash'] = $this->makeHash(array('username' => $params['email'],'password' => $user->password));
		$_SESSION['id'] = $params['id'];
		$_SESSION['user'] = $params;
		$_SESSION['level'] = base64_encode($params['level']);
	}
} 
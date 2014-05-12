<?php
/**
 * Project freecycle
 *
 * @author      Mehmet Ali Ergut <mim@taximact.com>
 * @copyright   Mehmet Ali Ergut
 * @version     0.1
 * @date        02/02/14 00:30
 * @package     00:30
 */

namespace Config;
use Predis;

/**
 * Class Status
 *
 * @package Config
 */
class Status
{
	/**
	 * @var \Predis\Client
	 */
	private $redis;

	function __construct()
	{
		$this->redis = new Predis\Client(Config::get(Config::env(),'Redis'));
		$this->redis->connect();
	}

	/**
	 * @param $key
	 * @param $data
	 *
	 * @return bool
	 */
	public function write($key,$data)
	{
		try {
			$this->redis->set($key,$data);
			return true;
		} catch (Predis\PredisException $e) {
			$this->redis->disconnect();
			return false;
		}
	}

	/**
	 * @param $key
	 *
	 * @return bool
	 */
	public function get($key)
	{
		try {
			return $this->redis->get($key);
		} catch (Predis\PredisException $e) {
			$this->redis->disconnect();
			return false;
		}
	}

	function __destruct()
	{
		$this->redis->disconnect();
	}

}

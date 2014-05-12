<?php
/**
 * Project freecycle
 *
 * @author      Mehmet Ali Ergut <mim@taximact.com>
 * @copyright   Mehmet Ali Ergut
 * @version     0.1
 * @date        02/02/14 00:26
 * @package     00:26
 */

namespace Config;
/**
 * Class Config
 *
 * @package Config
 */
class Config
{
	/**
	 * @var array
	 */
	private static $configData = array('db' => array(
			'live' => array(
				'DBTYPE' => 'mysql',
				'DBHOST' => 'localhost',
				'DBNAME' => '',
				'DBUSR' => '',
				'DBPSW' => ''
			),
			'development' => array(
				'DBTYPE' => 'mysql',
				'DBHOST' => '192.168.56.101',
				'DBNAME' => 'freecycle',
				'DBUSR' => 'root',
				'DBPSW' => '123456'
			)
		),
		'Redis' => array(
			'development' => array('scheme'=>'tcp', 'host' => '192.168.56.101','port'=> 6379,'password' => null),
			'production' => array('scheme'=>'tcp', 'host' => '127.0.0.1','port'=> 6379,'password' => null),
		),
		'defaultLanguage' => 'en',
		'languages' => array('en','tr'),
		'imagesProcess' => 'imagick',
		'mandrill' => array('key' => '', 'url'=>'https://mandrillapp.com/api/1.0/'),
	);

	/**
	 * @param $name
	 * @param null $type
	 *
	 * @return mixed
	 */
	public static function get($name,$type=null){
		return (!is_null($type))? self::$configData[$type][$name] : self::$configData[$name];
	}

	/**
	 * @return string
	 */
	public static function env()
	{
		return (isset($_SERVER['APP_ENV']) && ($_SERVER['APP_ENV'] == 'dev'))?'development':'production';
	}
}

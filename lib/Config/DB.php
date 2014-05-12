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

use PDO;

/**
 * Class DB
 *
 * @package Config
 */
class DB
{

	/**
	 * @var array
	 */
	private $connection;
	/**
	 * @var string
	 */
	private $database_name;

	/**
	 */
	function __construct(){
	}

	/**
	 * @return mixed
	 */
	public function get()
	{
		$this->database_name = (Config::env() == 'development')?'development':'live';
		$db_conf = Config::get($this->database_name,'db');
		try {
			if (!isset($this->connection[$this->database_name]) || !$this->connection[$this->database_name] instanceof \PDO ) {
				$this->connection[$this->database_name] = new \PDO($db_conf['DBTYPE'].":host=".$db_conf['DBHOST'].";dbname=".$db_conf['DBNAME'], $db_conf['DBUSR'], $db_conf['DBPSW'], array(
					PDO::MYSQL_ATTR_INIT_COMMAND =>  "SET NAMES utf8",
					PDO::ATTR_PERSISTENT => false
				));
				$this->connection[$this->database_name]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			return $this->connection[$this->database_name];
		} catch (\PDOException $e){
			trigger_error("PDO Connection error!! ".print_r($e,1), E_USER_ERROR);
			return false;
		}
	}
}

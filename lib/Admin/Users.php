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
use PDO;

/**
 * Class Users
 *
 * @package Admin
 */
class Users {
	/**
	 * @var object
	 */
	private $connection;
	/**
	 * @var array
	 */
	public $levels = array('passive','user','moderator','administrator');
	/**
	 *
	 */
	function __construct(DB $db)
	{
		$this->connection = $db;
	}

	/**
	 * @param $user_id
	 *
	 * @return bool
	 */
	public function get($user_id)
	{
		try {
			$selectQuery = $this->connection->get()->prepare("SELECT * FROM users WHERE id=:id");
			$selectQuery->bindParam(':id',$user_id,PDO::PARAM_INT);
			$selectQuery->execute();
			if($selectQuery->rowCount() > 0)
			{
				return $selectQuery->fetch(PDO::FETCH_OBJ);
			} else {
				return false;
			}
		} catch ( \PDOException $e){
			return false;
		}
	}

	/**
	 * @param $user_id
	 * @param array $user_data
	 *
	 * @return boolean
	 */
	public function update($user_id,array $user_data)
	{
		$auth = new Auth($this->connection);
		$user_data['password'] = $auth->makeHash($user_data);

		try {
			$updateQuery = $this->connection->get()->prepare("UPDATE users SET
															        username=:username,
															        password=:password,
															        level=:level,
															        add_date=CURDATE(),
															        update_ip=:ip
														        WHERE id=:id;");

			$updateQuery->bindParam(':id',$user_id,PDO::PARAM_INT);
			$updateQuery->bindParam(':username',$user_data['username'],PDO::PARAM_STR);
			$updateQuery->bindParam(':password',$user_data['password'],PDO::PARAM_STR);
			$updateQuery->bindParam(':level',$user_data['level'],PDO::PARAM_STR);
			$updateQuery->bindParam(':ip',$_SERVER['REMOTE_ADDR'],PDO::PARAM_STR);
			$updateQuery->execute();
			return true;
		} catch ( \PDOException $e){
			return false;
		}
	}

	/**
	 * @param $user_id
	 * @param $password
	 *
	 * @return bool
	 */
	public function updatePassword($user_id,$password)
	{
		$auth = new Auth($this->connection);
		$user = $this->get($user_id);
		$user_data = array('username' => $user->username, 'password' => $password);

		$user_data['password'] = $auth->makeHash($user_data);

		try {
			$updateQuery = $this->connection->get()->prepare("UPDATE users SET
															        password=:password,
															        add_date=CURDATE(),
															        update_ip=:ip
														        WHERE id=:id;");

			$updateQuery->bindParam(':id',$user_id,PDO::PARAM_INT);
			$updateQuery->bindParam(':password',$user_data['password'],PDO::PARAM_STR);
			$updateQuery->bindParam(':ip',$_SERVER['REMOTE_ADDR'],PDO::PARAM_STR);
			$updateQuery->execute();
			return true;
		} catch ( \PDOException $e){
			return false;
		}
	}

	/**
	 * @param $user_id
	 * @param null $state
	 *
	 * @return bool
	 */
	public function changeState($user_id,$state=null)
	{
		if(in_array($state,$this->levels))
		{
			try {
				$updateQuery = $this->connection->get()->prepare("UPDATE users TU SET
															        TU.level=:level,
															        update_ip=:ip
															       WHERE id=:id;");
				$updateQuery->bindParam(':id',$user_id,PDO::PARAM_INT);
				$updateQuery->bindParam(':level',$state,PDO::PARAM_STR);
				$updateQuery->bindParam(':ip',$_SERVER['REMOTE_ADDR'],PDO::PARAM_STR);

				$updateQuery->execute();
				return true;
			} catch ( \PDOException $e){
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * @param array $user_data
	 *
	 * @return bool
	 */
	public function create($user_data=array())
	{
		$auth = new Auth($this->connection);
		$user_data['password'] = $auth->makeHash($user_data);
		try {
			$createQuery = $this->connection->get()->prepare("INSERT INTO users SET
															        username=:username,
															        password=:password,
															        level=:level,
															        add_date=CURDATE(),
															        update_ip=:ip;");
			$createQuery->bindParam(':username',$user_data['username'],PDO::PARAM_STR);
			$createQuery->bindParam(':password',$user_data['password'],PDO::PARAM_INPUT_OUTPUT);
			$createQuery->bindParam(':level',$user_data['level'],PDO::PARAM_STR);
			$createQuery->bindParam(':ip',$_SERVER['REMOTE_ADDR'],PDO::PARAM_STR);

			$createQuery->execute();
			return true;
		} catch ( \PDOException $e){
			return false;
		}
	}

	/**
	 * @param $page
	 * @param $max
	 * @param $all
	 *
	 * @return bool
	 */
	public function lister($page, $max,$all=false)
	{
		$limit = ($all)?'':"LIMIT $page,$max";
		try {
			$selectQuery = $this->connection->get()->prepare("SELECT * FROM users $limit");
			$selectQuery->execute();
			if($selectQuery->rowCount() > 0)
			{
				return $selectQuery->fetchAll(PDO::FETCH_OBJ);
			} else {
				return false;
			}
		} catch ( \PDOException $e){
			return false;
		}
	}

	/**
	 * @return bool
	 */
	public function totalCount()
	{
		try {
			$countQuery = $this->connection->get()->prepare("SELECT * FROM users");
			$countQuery->execute();
			return $countQuery->rowCount();
		} catch( \PDOException $e) {
			return false;
		}
	}
} 
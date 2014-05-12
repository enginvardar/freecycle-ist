<?php
/**
 * Project freecycle
 *
 * @author      Mehmet Ali Ergut <mim@taximact.com>
 * @copyright   Mehmet Ali Ergut
 * @version     0.1
 * @date        01/02/14 03:32
 * @package     03:32
 */

namespace Home;

use Config\Config;
use PDO;
use Config\DB;

class Pages {
	private $db;
	function __construct(DB $db)
	{
		$this->db = $db;
	}

	public function add($title)
	{
		try {
			$insert = $this->db->get()->prepare("INSERT INTO static_pages SET title=:title");
			$insert->bindParam(':title',$title,PDO::PARAM_STR,100);
			$insert->execute();
			if($insert->rowCount() > 0)
			{
				return $this->db->get()->lastInsertId;
			} else {
				return false;
			}
		} catch (\PDOException $e)
		{
			return false;
		}
	}

	public function update($id,$title)
	{
		try {
			$update = $this->db->get()->prepare("UPDATE static_pages SET title=:title WHERE id=:id");
			$update->bindParam(':id',$id,PDO::PARAM_INT,11);
			$update->bindParam(':title',$title,PDO::PARAM_STR,100);
			$update->execute();
			if($update->rowCount() > 0)
			{
				return $this->db->get()->lastInsertId;
			} else {
				return false;
			}
		} catch (\PDOException $e)
		{
			return false;
		}
	}

	public function get($id,$language=false)
	{
		$language = ($language)?$language:Config::get('defaultLanguage');
		try {
			$select = $this->db->get()->prepare("SELECT * FROM
													static_pages P
													RIGHT JOIN
													static_data D ON P.id=D.sid
													WHERE
														P.id=:id AND
														D.language=:language");
			$select->bindParam(':id',$id,PDO::PARAM_INT);
			$select->bindParam(':language',$language,PDO::PARAM_STR,2);
			$select->execute();
			if($select->rowCount())
			{
				return $select->fetch();
			} else {
				return false;
			}
		} catch (\PDOException $e)
		{
			return false;
		}
	}

	public function delete($id)
	{
		try {
			$delete = $this->db->get()->prepare("DELETE FROM static_pages WHERE id=:id");
			$delete->bindParam(':id',$id,PDO::PARAM_INT,11);
			$delete->execute();
			if($delete->rowCount())
			{
				$this->deleteTranslation(false,$id);
			} else {
				return false;
			}
		} catch (\PDOException $e)
		{
			return false;
		}
	}

	public function listAll()
	{
		try {
			$select = $this->db->get()->prepare("SELECT * FROM static_pages");
			$select->execute();
			if($select->rowCount())
			{
				return $select->fetchAll();
			} else {
				return false;
			}
		} catch (\PDOException $e)
		{
			return false;
		}
	}

	public function getTranslation($id)
	{
		try {
			$select = $this->db->get()->prepare("SELECT * FROM static_data WHERE id=:id");
			$select->bindParam(':id',$id,PDO::PARAM_INT,11);
			$select->execute();
			if($select->rowCount())
			{
				return $select->fetch();
			} else {
				return false;
			}
		} catch (\PDOException $e)
		{
			return false;
		}
	}

	public function insertTranslation($data)
	{
		try {
			$insert = $this->db->get()->prepare("INSERT INTO static_data SET
													sid=:sid,
													language=:language,
													title=:title,
													body=:body,
													image=:image,
													keywords=:keywords,
													add_time=NOW()");
			$insert->bindParam(':sid',$data['sid'],PDO::PARAM_INT,11);
			$insert->bindParam(':language',$data['language'],PDO::PARAM_STR,2);
			$insert->bindParam(':title',$data['title'],PDO::PARAM_NULL|PDO::PARAM_STR,100);
			$insert->bindParam(':body',$data['body'],PDO::PARAM_NULL|PDO::PARAM_STR);
			$insert->bindParam(':image',$data['image'],PDO::PARAM_NULL|PDO::PARAM_STR);
			$insert->bindParam(':keywords',$data['keywords'],PDO::PARAM_NULL|PDO::PARAM_STR,100);
			$insert->execute();
			if($insert->rowCount() > 0)
			{
				return $this->db->get()->lastInsertId;
			} else {
				return false;
			}
		} catch (\PDOException $e)
		{
			return false;
		}
	}

	public function updateTranslation($id,$data)
	{
		try {
			$update = $this->db->get()->prepare("UPDATE static_data SET
													sid=:sid,
													language=:language,
													title=:title,
													body=:body,
													image=:image,
													keywords=:keywords
												WHERE id=:id");
			$update->bindParam(':id',$id,PDO::PARAM_INT,11);
			$update->bindParam(':sid',$data['sid'],PDO::PARAM_INT,11);
			$update->bindParam(':language',$data['language'],PDO::PARAM_STR,2);
			$update->bindParam(':title',$data['title'],PDO::PARAM_NULL|PDO::PARAM_STR,100);
			$update->bindParam(':body',$data['body'],PDO::PARAM_NULL|PDO::PARAM_STR);
			$update->bindParam(':image',$data['image'],PDO::PARAM_NULL|PDO::PARAM_STR);
			$update->bindParam(':keywords',$data['keywords'],PDO::PARAM_NULL|PDO::PARAM_STR,100);
			$update->execute();
			if($update->rowCount() > 0)
			{
				return $this->db->get()->lastInsertId;
			} else {
				return false;
			}
		} catch (\PDOException $e)
		{
			return false;
		}
	}

	public function deleteTranslation($id=false,$sid=false)
	{
		$filter = ($id)?"id=:id":"sid=:id";
		$selected = ($id)?$id:$sid;
		try {
			$delete = $this->db->get()->prepare("DELETE FROM static_data WHERE $filter");
			$delete->bindParam(':id',$selected,PDO::PARAM_INT,11);
			$delete->execute();
			if($delete->rowCount())
			{
				return true;
			} else {
				return false;
			}
		} catch (\PDOException $e)
		{
			return false;
		}
	}

	public function listTranslation($id=false,$sid=false,$language=false)
	{
		if($id){
			$filter = "id=:id";
			$selected = $id;
		} elseif($sid){
			$filter = "sid=:id";
			$selected = $sid;
		} elseif($language){
			$filter = "language=:id";
			$selected = $language;
		}

		try {
			$select = $this->db->get()->prepare("DELETE FROM static_data WHERE $filter");
			$select->bindParam(':id',$selected,PDO::PARAM_INT,11);
			$select->execute();
			if($select->rowCount())
			{
				return $select->fetchAll();
			} else {
				return false;
			}
		} catch (\PDOException $e)
		{
			return false;
		}
	}
} 
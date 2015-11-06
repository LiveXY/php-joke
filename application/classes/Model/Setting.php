<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Setting extends Model {
	//版本
	public function getVersions() {
		$sql = "select * from version order by vid desc";
		return $this->db->query(Database::SELECT, $sql, true);
	}
	public function getNewVersion() {
		$sql = "select * from version where status=1 order by vid desc limit 1";
		$query = $this->db->query(Database::SELECT, $sql, true);
		return $query->valid() ? $query->current() : false;
	}
	public function insertVersion($data) {
		$sql = 'insert into version('.$this->fields($data).') values('.$this->values($data).')';
		return $this->db->query(Database::INSERT, $sql, true);
	}
	public function updateVersion($id, $data) {
		$sql = "update version set ".$this->set($data)." where vid={$id}";
		return $this->db->query(Database::UPDATE, $sql, true);
	}
	public function deleteVersion($id) {
		$sql = "delete from version where vid={$id}";
		return $this->db->query(Database::DELETE, $sql, true);
	}
}
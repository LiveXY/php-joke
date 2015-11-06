<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Setting extends Model {
	public function getJokes($type = 0, $tid = 0, $cid = 0, $page = 1, $pageSize = 10) {
		$sql = '';
		if ($tid == 0) {
			$sql = "select * from joke_info as a where type=$type";
		} else {
			$sql = "select a.* from joke_info as a inner join joke_tags as b on a.jid=b.jid and b.tid=$tid where type=$type";
		}
		$sql .= $cid == 1 ? " order by shares desc, likes desc" : " order by a.jid desc";

		$offset = ($page - 1) * $pageSize;
		$limit = $pageSize;
		$sql .= " LIMIT {$limit} OFFSET {$offset}";
		return $this->db->query(Database::SELECT, $sql, true);
	}
	public function updateJokeLikes($id) {
		$sql = "update joke_info set likes=likes+1 where tid={$id}";
		return $this->db->query(Database::UPDATE, $sql, true);
	}
	public function updateJokeShares($id) {
		$sql = "update joke_info set shares=shares+1 where tid={$id}";
		return $this->db->query(Database::UPDATE, $sql, true);
	}
	//Tags
	public function getTags(){
		$sql = "select * from tags order by orderby desc";
		return $this->db->query(Database::SELECT, $sql, true);
	}
	public function insertTags($data) {
		$sql = 'insert into tags('.$this->fields($data).') values('.$this->values($data).')';
		return $this->db->query(Database::INSERT, $sql, true);
	}
	public function updateTags($id, $data) {
		$sql = "update tags set ".$this->set($data)." where id={$id}";
		return $this->db->query(Database::UPDATE, $sql, true);
	}
	public function deleteTags($id) {
		$sql = "delete from tags where id={$id}";
		return $this->db->query(Database::DELETE, $sql, true);
	}
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
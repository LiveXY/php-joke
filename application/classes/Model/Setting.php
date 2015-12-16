<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Setting extends Model {
	public function getUserJoke($id) {
		$sql = "select * from user_joke where jid=$id";
		$query = $this->db->query(Database::SELECT, $sql, true);
		return $query->valid() ? $query->current() : false;
	}
	public function getAuditList($key = '', $page = 1, $pageSize = 25) {
		$sql = "select * from user_joke where 1=1 ";
		if ($key) $sql .= " and (title like '%$key%' or joke like '%$key%')";
		$sql .= ' order by jid desc ';

		$offset = ($page - 1) * $pageSize;
		$limit = $pageSize;
		$sql .= " LIMIT {$limit} OFFSET {$offset}";
		return $this->db->query(Database::SELECT, $sql, true);
	}
	public function getAuditCount($key = '') {
		$sql = "select count(jid) count from user_joke where 1=1 ";
		if ($key) $sql .= " and (title like '%$key%' or joke like '%$key%')";
		return $this->db->query(Database::SELECT, $sql, true)->current()->count;
	}
	public function deleteUserJoke($id) {
		$sql = "delete from user_joke where jid={$id}";
		return $this->db->query(Database::DELETE, $sql, true);
	}
	public function updateUserJoke($id, $data) {
		$sql = "update user_joke set ".$this->set($data)." where jid={$id}";
		return $this->db->query(Database::UPDATE, $sql, true);
	}
	public function audit($key) {
		$ex = empty($key) ? '' : " and (title like '%$key%' or joke like '%$key%') ";
		$sql = "select * from user_joke where 1=1 $ex order by random() limit 10";
		return $this->db->query(Database::SELECT, $sql, true);
	}
	public function myJokes($uid, $page = 1, $pageSize = 10) {
		$sql = "select a.* from joke_info as a inner join user_like as b on a.jid=b.jid and uid=$uid order by b.ltime desc";

		$offset = ($page - 1) * $pageSize;
		$limit = $pageSize;
		$sql .= " LIMIT {$limit} OFFSET {$offset}";
		return $this->db->query(Database::SELECT, $sql, true);
	}
	public function getJokeCountByMax($id) {
		$sql = "select count(jid) count from joke_info where jid>$id";
		return intval($this->db->query(Database::SELECT, $sql, true)->current()->count);
	}
	public function getJokeMore($id, $top = 20) {
		$sql = "select * from joke_info where jid<$id order by jid desc limit $top";
		return $this->db->query(Database::SELECT, $sql, true);
	}
	public function getJokes($type = 0, $tid = 0, $cid = 0, $key = '', $page = 1, $pageSize = 10) {
		$sql = '';
		if ($tid == 0) {
			$sql = "select * from joke_info as a where type=$type";
		} else {
			$sql = "select a.* from joke_info as a inner join joke_tags as b on a.jid=b.jid and b.tid=$tid where type=$type";
		}
		if ($key) $sql .= " and (title like '%$key%' or joke like '%$key%')";
		$sql .= $cid == 1 ? " order by shares desc, likes desc" : " order by a.jid desc";

		$offset = ($page - 1) * $pageSize;
		$limit = $pageSize;
		$sql .= " LIMIT {$limit} OFFSET {$offset}";
		return $this->db->query(Database::SELECT, $sql, true);
	}
	public function getJokeCount($type = 0, $tid = 0, $cid = 0, $key = '') {
		$sql = '';
		if ($tid == 0) {
			$sql = "select count(a.jid) count from joke_info as a where type=$type";
		} else {
			$sql = "select count(a.jid) count from joke_info as a inner join joke_tags as b on a.jid=b.jid and b.tid=$tid where type=$type";
		}
		if ($key) $sql .= " and (title like '%$key%' or joke like '%$key%')";
		return $this->db->query(Database::SELECT, $sql, true)->current()->count;
	}
	public function getJoke($id) {
		$sql = "select * from joke_info where jid=$id";
		$query = $this->db->query(Database::SELECT, $sql, true);
		return $query->valid() ? $query->current() : false;
	}
	public function updateJokeLikes($id) {
		$sql = "update joke_info set likes=likes+1 where jid={$id}";
		return $this->db->query(Database::UPDATE, $sql, true);
	}
	public function updateUserLike($id, $uid) {
		$sql = "update user_like set likes=likes+1 where jid={$id} and uid={$uid}";
		$result = $this->db->query(Database::UPDATE, $sql, true);
		if (!$result) {
			$time = TIMESTAMP;
			$sql = "insert into user_like(uid, jid, likes, ltime) values($uid, $id, 1, $time)";
			return $this->db->query(Database::INSERT, $sql, true);
		}
		return $result;
	}
	public function updateJokeShares($id) {
		$sql = "update joke_info set shares=shares+1 where jid={$id}";
		return $this->db->query(Database::UPDATE, $sql, true);
	}
	public function insertJoke($data) {
		$sql = 'insert into joke_info('.$this->fields($data).') values('.$this->values($data).')';
		return $this->db->query(Database::INSERT, $sql, true);
	}
	public function updateJoke($id, $data) {
		$sql = "update joke_info set ".$this->set($data)." where jid={$id}";
		return $this->db->query(Database::UPDATE, $sql, true);
	}
	public function deleteJoke($id) {
		$sql = "delete from joke_info where jid={$id}";
		return $this->db->query(Database::DELETE, $sql, true);
	}
	public function deleteJokeTags($id) {
		$sql = "delete from joke_tags where jid={$id}";
		return $this->db->query(Database::DELETE, $sql, true);
	}
	public function insertJokeTags($data) {
		if (count($data) == 0) return false;
		$sql = 'insert into joke_tags('.$this->fields($data[0]).') values'.$this->valuesEx($data);
		return $this->db->query(Database::INSERT, $sql, true);
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
		$sql = "update tags set ".$this->set($data)." where tid={$id}";
		return $this->db->query(Database::UPDATE, $sql, true);
	}
	public function deleteTags($id) {
		$sql = "delete from tags where tid={$id}";
		return $this->db->query(Database::DELETE, $sql, true);
	}
	//用户反馈
	public function getFeedbackList($page, $pageSize = 25){
		$sql 	= "select * from user_feedback order by fid desc";

		$offset = ($page - 1) * $pageSize;
		$limit = $pageSize;
		$sql .= " limit {$limit} offset {$offset}";

		return $this->db->query(Database::SELECT, $sql, true);
	}
	public function getFeedbackCount(){
		$sql 	= "select count(fid) count from user_feedback";
		$query = $this->db->query(Database::SELECT, $sql, true);
		return $query->current()->count;
	}
	public function insertFeedback($data) {
		$sql = 'insert into user_feedback('.$this->fields($data).') values('.$this->values($data).')';
		return $this->db->query(Database::INSERT, $sql, true);
	}
	public function updateFeedback($id,$data) {
		$sql = "update user_feedback set ".$this->set($data)." where fid={$id}";
		return $this->db->query(Database::UPDATE, $sql, true);
	}
	public function deleteFeedback($id) {
		$sql = "delete from user_feedback where fid={$id}";
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
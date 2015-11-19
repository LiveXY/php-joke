<?php defined('SYSPATH') OR die('No direct script access.');

class Model_App extends Model {

	public function __construct() {
		parent::__construct();
	}

	//在线用户编号列表
	public function onlineUserIDs(){
		$sql = "select user_id as uid from user_online";
		return $this->db->query(Database::SELECT, $sql, true);
	}

	public function crontab_report_user_summary($day){
		$begin = strtotime($day);
		$end = $begin + 86400;
		$sql = "
select
(select count(uid) from sys_user) totals,
(select count(uid) from sys_user where reg_date>=$begin and reg_date<$end) registers,
(select count(distinct(uid)) from log_user_login where ldate>=$begin and ldate<$end) logins
";
		$query = $this->db->query(Database::SELECT, $sql, true);
		return $query->current();
	}
	public function insertReportUserSummary($data) {
		$sql = 'update report_user_summary set '.$this->set($data).' where day='.$data['day'];
		$result = $this->db->query(Database::UPDATE, $sql, true);
		if (!$result) {
			$sql = 'insert into report_user_summary('.$this->fields($data).') values('.$this->values($data).')';
			$result = $this->db->query(Database::INSERT, $sql, true);
		}
		return $result;
	}
	public function crontab_report_platform_summary($day){
		$begin = strtotime($day);
		$end = $begin + 86400;
		$sql = "select bundleid, count(uid) count from sys_user group by bundleid";
		$list1 = $this->db->query(Database::SELECT, $sql, true);
		$sql = "select bundleid,count(uid) count from sys_user where reg_date>=$begin and reg_date<$end group by bundleid";
		$list2 = $this->db->query(Database::SELECT, $sql, true);
		$sql = "select bundleid, count(a.uid) count from (select distinct(uid) uid from log_user_login where ldate>=$begin and ldate<$end) as a inner join sys_user as b on a.uid=b.uid group by bundleid";
		$list3 = $this->db->query(Database::SELECT, $sql, true);
		return array('r1'=>$list1, 'r2'=>$list2, 'r3'=>$list3);
	}
	public function insertReportPlatformSummary($day, $data) {
		$sql = "delete from report_platform_summary where day=$day";
		$this->db->query(DATABASE::DELETE, $sql, true);
		$list = array();
		foreach ($data as $key => $value) {
			$value['day'] = $day;
			$value['platform'] = $key;
			$list[] = $value;
		}
		if (count($list) > 0) {
			$sql = 'insert into report_platform_summary('.$this->fields($list[0]).') values'.$this->valuesEx($list);
			$result = $this->db->query(Database::INSERT, $sql, true);
		}
		return 1;
	}
	public function create_tables(){
		return true;
		//$sql = "";
		//return $this->db->query(Database::INSERT, $sql, true);
	}

	//更新管理员在线状态
	public function updateAdminOnline($user_id, $url, $ip, $time, $request){
		$data = array('user_id'=>$user_id,'user_url'=>$url,'url_ip'=>$ip,'last_time'=>TIMESTAMP);
		$sql = 'update user_online set '.$this->set($data).' where user_id='.$user_id;
		$result = $this->db->query(Database::UPDATE, $sql, true);
		if (!$result) {
			$sql = 'insert into user_online('.$this->fields($data).') values('.$this->values($data).')';
			$this->db->query(Database::INSERT, $sql, true);
		}

		$sql = "update sys_user set online = 1, online_times = online_times + $time where uid=$user_id";
		$this->db->query(Database::UPDATE, $sql, true);

		$data = array('uid'=>$user_id,'url'=>$url,'request'=>$request,'ip'=>$ip,'ltime'=>TIMESTAMP);
		$sql = 'insert into log_admin_op('.$this->fields($data).') values('.$this->values($data).')';
		$this->db->query(Database::INSERT, $sql, true);
		return 1;
	}
	public function updateUserOnline($user_id, $url, $ip, $time){
		$data = array('user_id'=>$user_id,'user_url'=>$url,'url_ip'=>$ip,'last_time'=>TIMESTAMP);
		$sql = 'update user_online set '.$this->set($data).' where user_id='.$user_id;
		$result = $this->db->query(Database::UPDATE, $sql, true);
		if (!$result) {
			$sql = 'insert into user_online('.$this->fields($data).') values('.$this->values($data).')';
			$this->db->query(Database::INSERT, $sql, true);
		}

		$sql = "update sys_user set online = 1, online_times = online_times + $time where uid=$user_id";
		$this->db->query(Database::UPDATE, $sql, true);
		return 1;
	}
	public function deleteOnline($uid) {
		$sql = "delete from user_online where user_id=$uid";
		return $this->db->query(DATABASE::DELETE, $sql, true);
	}

	public function logUserLogin($data) {
		$sql = 'insert into log_user_login('.$this->fields($data).') values('.$this->values($data).')';
		return $this->db->query(Database::INSERT, $sql, true);
	}
	public function insertUserJoke($data) {
		$sql = 'insert into user_joke('.$this->fields($data).') values('.$this->values($data).')';
		return $this->db->query(Database::INSERT, $sql, true);
	}

}

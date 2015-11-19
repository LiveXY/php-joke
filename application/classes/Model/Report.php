<?php
class Model_Report extends Model {
	//注册用户
	public function getReportReg($month){
		$sql = "SELECT day,registers,logins FROM report_user_summary  where 1";
		if(strlen($month) > 0){
			$month_begin = date('Ym',$month).'01';
			$sql .=" and day >=".$month_begin." and day <".date('Ymd',strtotime("+1 months",strtotime($month_begin)));
		}
		$sql .= " order by day asc";
		return $this->db->query(Database::SELECT, $sql, true);
	}
	//用户总表
	public function getReportUserSummary($month){
		$sql = "SELECT * FROM report_user_summary  where 1";
		if(strlen($month) > 0){
			$month_begin = $month.'01';
			$sql .=" and day >=".$month_begin." and day <".date('Ymd',strtotime("+1 months",strtotime($month_begin)));
		}
		$sql .= " order by day desc";
		return $this->db->query(Database::SELECT, $sql, true);
	}
}
<?php
class Controller_Admin_Report extends AdminController {
	public function action_index(){}
	//用户总表
	public function action_user_all(){
		$this->checkFunction("UserReport");
		$month = $this->request->query('month');
		if (!$month) $month = date('Ym');

		$DATA = array();
		$DATA['month'] 		= $month;
		$DATA['list'] 		= Model::factory('Report')->getReportUserSummary($month);

		View::set_global('title', '总表');
		echo $this->iframeView('admin/report/user_all', $DATA);
	}
	//注册用户
	public function action_reg(){
		$this->checkFunction("RegReport");
		$month = $this->request->query('month');
		$month = !$month ? strtotime(date('Ymd')):strtotime($month.'01');

		$DATA = array();
		$DATA['timestamp'] 		= $month;
		$list 					= Model::factory('Report')->getReportReg($month);
		$DATA['rows_month'] 	= array();
		if( $list ){
			foreach($list as $key => $value){
				$DATA['rows_month']['registers'][date('Y-m-d',strtotime($value->day))] = intval($value->registers);
				$DATA['rows_month']['logins'][date('Y-m-d',strtotime($value->day))] = intval($value->logins);
			}
		}
		$DATA['user_right'] = $this->funcOp('RegReport');
		View::set_global('title', '注册用户');
		echo $this->iframeView('admin/report/reg', $DATA);
	}
}
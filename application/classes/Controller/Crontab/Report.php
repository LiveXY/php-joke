<?php defined('SYSPATH') OR die('No direct script access.');

set_time_limit(240);

class Controller_Crontab_Report extends BaseController {

	public function action_day() {
		$day = intval($this->request->query('day'));
		if ($day == 0) $day = intval(date('Ymd'));
		if ($day < 0) $day = intval(date('Ymd', strtotime($day.' day', time())));

		$info = Model::factory('App')->crontab_report_user_summary($day);
		if ($info) {
			$data = array('day'=>$day, 'totals'=>$info->totals, 'registers'=>$info->registers, 'logins'=>$info->logins);
			$info = Model::factory('App')->insertReportUserSummary($data);
		}
		$list = Model::factory('App')->crontab_report_platform_summary($day);
		if ($list) {
			$r1 = $list['r1'];
			$r2 = $list['r2'];
			$r3 = $list['r3'];
			$data = array();
			foreach($r1 as $info) {
				$bundleid = trim(str_replace('com.livexy.joke', '', $info->bundleid), "-");
				$data[$bundleid] = array('totals'=>$info->count + 0, 'registers'=>0, 'logins'=>0);
			}
			foreach($r2 as $info) {
				$bundleid = trim(str_replace('com.livexy.joke', '', $info->bundleid), "-");
				$data[$bundleid]['registers'] = $info->count + 0;
			}
			foreach($r3 as $info) {
				$bundleid = trim(str_replace('com.livexy.joke', '', $info->bundleid), "-");
				$data[$bundleid]['logins'] = $info->count + 0;
			}
			Model::factory('App')->insertReportPlatformSummary($day, $data);
		}

		$list = Model::factory('Setting')->audit('',1);
		if ($list && count($list) == 1) {
			$v = $list[0];
			$joke = array('title'=>$v->title, 'joke'=>$v->joke, 'type'=>0, 'ltime'=>TIMESTAMP,'score'=>10, 'tags'=> '');
			$result = Model::factory('Setting')->insertJoke($joke);
			if ($result) Model::factory('Setting')->deleteUserJoke($v->jid);
		}
		exit;
	}

}
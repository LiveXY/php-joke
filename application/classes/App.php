<?php defined('SYSPATH') OR die('No direct script access.');

class App {
	//登录注册
	public static function Auth($obj){
		Util::WriteLog('mobile', 'Auth');
		$platform = $obj->req('platform');
		$uuid = $obj->req('uuid');
		$locale = $obj->req('locale');
		$bundleid = $obj->req('bundleid');
		$ver = $obj->req('ver');
		$network = $obj->req('network');
		$vid = intval($obj->req('vid'));

		if (!$locale) $locale = Util::determineLang();
		$locale = $obj->getLocale($locale);

		if (!$uuid || !$platform || !$locale || !$bundleid) $obj->paramError();

		$obj->user = Model::factory('Sys')->getUserByName($uuid);

		if (!$obj->user) {
			$obj->uid = Model::factory("Sys")->register($uuid, '', $platform, $locale, $bundleid);
			if (!$obj->uid) exit('no user');

			Util::SmallLog("mobile", "new:uid=".$obj->uid);
		} else {
			$obj->uid = $obj->user->uid;
		}

		$obj->user = $obj->login($obj->uid);
		App::updateLoginData($obj->user, 1, $ver, $uuid, $network);

		$pkey = Util::makePkey($obj->uid, TIMESTAMP, $locale, 1);
		if (!$obj->user) $obj->paramError();
		return $obj->jsonp(array('ret'=>0, 'login'=>0, 'user'=>self::getUser($obj->user, $vid), 'sessionKey'=>$pkey));
	}
	public static function Logout($obj) {
		$obj->checkData();

		Model::factory('App')->deleteOnline($obj->uid);
		$obj->jsonp(array('ret'=>0));
	}
	public static function Version($obj) {
		$data = array('ret'=>0, 'list'=>array());
		$version = intval($obj->req('version'));
		$list = CacheManager::getVersions();
		$maxID = 0; $maxName = ''; $index = 1;
		foreach($list as $info) {
			if ($info->status == '0' || $index > 3) continue;
			if ($info->vid > $maxID && $info->vid > $version) { $maxID = $info->vid; $maxName = $info->vname; }
			$data['list'][] = array('vid'=>$info->vid, 'vname'=>$info->vname, 'vtext'=>$info->vtext, 'time'=>date('Y-m-d H:i:s',$info->ltime));
			$index++;
		}
		if ($maxID) {
			$data['url'] = RESOURCE.'upload/version/'.$maxName.'.zip';
			$data['vname'] = $maxName;
			$data['vid'] = $maxID;
		}
		return $obj->jsonp($data);
	}
	//用户信息
	public static function Info($obj){
		$obj->checkData();

		if (!$obj->user) $obj->paramError();
		$obj->jsonp(array('ret'=>0, 'user'=>self::getUser($obj->user)));
	}
	//反馈
	public static function Feedback($obj){
		$obj->checkData();
		$text = KFilter::Filter($obj->req('text'));
		if (!$text) return $obj->jsonp(array('ret'=>1, "msg" => $obj->lang['operator_failure']));
		$result = Model::factory('Setting')->insertFeedback(array('uid'=>$obj->uid, 'feedback'=>$text, 'ltime'=>TIMESTAMP));
		if (!$result) return $obj->jsonp(array('ret'=>1, "msg" => $obj->lang['operator_failure']));
		return $obj->jsonp(array('ret'=>0));
	}
	private static function getUser($user, $vid = 0) {
		unset($user->password, $user->user_salt, $user->bundleid);
		$user->update = 0;
		if ($vid > 0) {
			$info = CacheManager::getNewVersion();
			if ($info && $info->vid > $vid) $user->update = 1;
		}
		return $user;
	}
	//更新用户登录数据
	private static function updateLoginData($user, $type = 0, $ver = '', $uuid = null, $network = null) {
		if (!$user) return false;
		$data = array();
		$data["login_times"] = $user->login_times + 1;
		$data['login_ip'] = IpLocation::getIP();
		$data['login_date'] = TIMESTAMP;
		if (empty($user->nickname)) $data['nickname'] = Util::randName();

		Model::factory("Sys")->updateUser($user->uid, $data);
		CacheManager::removeUser($user->uid);

		if (!$uuid) $uuid = $user->uuid;

		Model::factory("App")->logUserLogin(array('uid'=>$user->uid, 'utype'=>$type, 'ldate'=>TIMESTAMP, 'ip'=>IpLocation::getIP(), 'ver'=>$ver?:'', 'uuid'=>$uuid?:'', 'network'=>$network?:''));
	}
}
<?php defined('SYSPATH') OR die('No direct script access.');

class App {
	public static function Messages($obj){
		$data = array('ret'=>0);
		$maxJoke = intval($obj->req('maxJoke'));
		if ($maxJoke > 0) {
			$data['newJokes'] = Model::factory('Setting')->getJokeCountByMax($maxJoke);
		}
		return $obj->jsonp($data);
	}
	public static function Down($obj) {
		$t = 0;
		$p = Util::getMobile();
		if ($p && $p['iphone']) $t = 1;
		if ($p && $p['ipad']) $t = 1;
		if ($p && $p['android']) $t = 2;
		echo View::factory('down', array('t'=>$t));
	}
	public static function AuditDelete($obj) {
		$obj->checkData();
		$admin = CacheManager::getAdmin($obj->uid);
		if (!$admin) $obj->paramError();

		$data = array('ret'=>0);
		$id = intval($obj->req('id'));

		if ($id < 1) $obj->paramError();
		$result = Model::factory('Setting')->deleteUserJoke($id);
		$data['ret'] = $result ? 0 : 1;
		return $obj->jsonp($data);
	}
	public static function AuditPost($obj){
		$obj->checkData();
		$admin = CacheManager::getAdmin($obj->uid);
		if (!$admin) $obj->paramError();

		$data = array('ret'=>0);
		$id = intval($obj->req('id'));
		$title = $obj->req('title');
		$text = $obj->req('text');
		$tags = $obj->req('tags');
		$score = intval($obj->req('score'));
		if (!empty($tags)) $tags = explode(';', trim($tags, ';'));
		if ($id < 1 || empty($text) || empty($tags) || !is_array($tags) || $score < 1 || $score > 100) $obj->paramError();
		$result = Model::factory('Setting')->deleteUserJoke($id);
		//$result = Model::factory('Setting')->updateUserJoke($id, array('status'=>1));
		if ($result) {
			$joke = array('title'=>$title, 'joke'=>$text, 'type'=>0, 'ltime'=>TIMESTAMP,'score'=>$score, 'tags'=> implode(';', $tags));
			$result = Model::factory('Setting')->insertJoke($joke);
			if ($result) {
				$jid = $result[0]; $joke = array();
				foreach ($tags as $tid) {
					if ($tid < 1) continue;
					$joke[] = array('jid'=>$jid, 'tid'=>$tid);
				}
				Model::factory('Setting')->insertJokeTags($joke);
			}
		}
		$data['ret'] = $result ? 0 : 1;
		return $obj->jsonp($data);
	}
	public static function Audit($obj){
		$obj->checkData();
		$admin = CacheManager::getAdmin($obj->uid);
		if (!$admin) $obj->paramError();

		$tag = intval($obj->req('tag'));
		$key = $obj->req('key');

		$data = array('ret'=>0, 'list'=>array(), 'sign'=>md5(uniqid()));

		$list = Model::factory('Setting')->audit($key);
		foreach ($list as $v) {
			array_push($data['list'], array(
				'id'=>$v->jid,
				'title'=> empty($v->title) ? '' : Util::aes_encode($obj->uid, $data['sign'], $v->title),
				'text'=> Util::aes_encode($obj->uid, $data['sign'], str_replace("\n", '<br/>', $v->joke)),
			));
		}
		if ($tag == 1) {
			$data['tags'] = array();
			$list = CacheManager::getTags();
			foreach ($list as $k => $v)
				if ($k < 30 && !empty($v->title)) array_push($data['tags'], array(
					'id'=>$k,
					'title'=>Util::aes_encode($obj->uid, $data['sign'], $v->title),
					'totals'=>$v->totals+0
				));
		}
		return $obj->jsonp($data);
	}
	public static function Likes($obj){
		$obj->checkData();
		$page = intval($obj->req('page'));
		if ($page < 1) $page = 1;

		$data = array('ret'=>0, 'list'=>array(), 'sign'=>md5(uniqid()));

		$list = Model::factory('Setting')->myJokes($obj->uid, $page);
		foreach ($list as $v) {
			array_push($data['list'], array(
				'id'=>$v->jid,
				'title'=> empty($v->title) ? '' : Util::aes_encode($obj->uid, $data['sign'], $v->title),
				'text'=> Util::aes_encode($obj->uid, $data['sign'], str_replace("\n", '<br/>', $v->joke)),
				'img'=> empty($v->img) ? '' : Util::aes_encode($obj->uid, $data['sign'], BASEURI.'client/upload/joke-img/'.$v->img),
				'time'=>Util::formatTime2($v->ltime),
				'width'=>$v->width,
				'height'=>$v->height,
				'type'=>$v->type,
				'likes'=>$v->likes,
				'shares'=>$v->shares,
			));
		}
		return $obj->jsonp($data);
	}
	//笑话
	public static function Joke($obj) {
		$obj->checkData();

		$data = array('ret'=>0, 'list'=>array(), 'sign'=>md5(uniqid()));

		$tid = intval($obj->req('tid'));
		$cid = intval($obj->req('cid'));
		$page = intval($obj->req('page'));
		$key = $obj->req('key');

		if ($tid < 1) $tid = 0;
		if ($cid < 1) $cid = 0;
		if ($page < 1) $page = 1;

		if ($tid < 1 && $page == 1 && empty($key)) {
			$data['tags'] = array();
			$list = CacheManager::getTags();
			foreach ($list as $k => $v)
				if ($k < 30 && !empty($v->title)) array_push($data['tags'], array(
					'id'=>$k,
					'title'=>Util::aes_encode($obj->uid, $data['sign'], $v->title),
					'totals'=>$v->totals+0
				));
		}

		$list = Model::factory('Setting')->getJokes(0, $tid, $cid, $key, $page);

		foreach ($list as $v) {
			array_push($data['list'], array(
				'id'=>$v->jid,
				'title'=> empty($v->title) ? '' : Util::aes_encode($obj->uid, $data['sign'], $v->title),
				'text'=> Util::aes_encode($obj->uid, $data['sign'], str_replace("\n", '<br/>', $v->joke)),
				'img'=> empty($v->img) ? '' : Util::aes_encode($obj->uid, $data['sign'], BASEURI.'client/upload/joke-img/'.$v->img),
				'time'=>Util::formatTime2($v->ltime),
				'likes'=>$v->likes,
				'shares'=>$v->shares,
			));
		}

		return $obj->jsonp($data);
	}
	//喜欢笑话
	public static function JokeLike($obj){
		$obj->checkData();
		$data = array('ret'=>0);
		$id = intval($obj->req('id'));
		if ($id < 1) $obj->paramError();
		$result = Model::factory('Setting')->updateJokeLikes($id);
		if ($result) Model::factory('Setting')->updateUserLike($id, $obj->uid);
		return $obj->jsonp($data);
	}
	//分享笑话
	public static function JokeShare($obj){
		$obj->checkData();
		$data = array('ret'=>0);
		$id = intval($obj->req('id'));
		if ($id < 1) $obj->paramError();
		Model::factory('Setting')->updateJokeShares($id);
		return $obj->jsonp($data);
	}
	//美图
	public static function Meitu($obj) {
		$obj->checkData();

		$data = array('ret'=>0, 'list'=>array(), 'sign'=>md5(uniqid()));

		$tid = intval($obj->req('tid'));
		$cid = intval($obj->req('cid'));
		$page = intval($obj->req('page'));

		if ($tid < 1) $tid = 0;
		if ($cid < 1) $cid = 0;
		if ($page < 1) $page = 1;

		if ($tid < 1 && $page == 1) {
			$data['tags'] = array();
			$list = CacheManager::getTags();
			foreach ($list as $k => $v)
				if ($k < 30 && !empty($v->title)) array_push($data['tags'], array(
					'id'=>$k,
					'title'=>$v->title,
					'totals'=>$v->totals+0
				));
		}

		$list = Model::factory('Setting')->getJokes(1, $tid, $cid, '', $page);

		foreach ($list as $v) {
			array_push($data['list'], array(
				'id'=>$v->jid,
				'title'=> empty($v->title) ? '' : Util::aes_encode($obj->uid, $data['sign'], $v->title),
				'text'=> Util::aes_encode($obj->uid, $data['sign'], str_replace("\n", '<br/>', $v->joke)),
				'img'=> empty($v->img) ? '' : Util::aes_encode($obj->uid, $data['sign'], BASEURI.'client/upload/joke-img/'.$v->img),
				'time'=>Util::formatTime2($v->ltime),
				'width'=>$v->width,
				'height'=>$v->height,
				'likes'=>$v->likes,
				'shares'=>$v->shares,
			));
		}

		return $obj->jsonp($data);
	}
	//喜欢美图
	public static function MeituLike($obj){
		$obj->checkData();
		$data = array('ret'=>0);
		$id = intval($obj->req('id'));
		if ($id < 1) $obj->paramError();
		Model::factory('Setting')->updateJokeLikes($id);
		return $obj->jsonp($data);
	}
	//分享美图
	public static function MeituShare($obj){
		$obj->checkData();
		$data = array('ret'=>0);
		$id = intval($obj->req('id'));
		if ($id < 1) $obj->paramError();
		Model::factory('Setting')->updateJokeShares($id);
		return $obj->jsonp($data);
	}
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
		$uname = $obj->req('uname');

		if (!$locale) $locale = Util::determineLang();
		$locale = $obj->getLocale($locale);

		if (!$uuid || !$platform || !$locale || !$bundleid || $uuid == 'admin') $obj->paramError();

		$obj->user = Model::factory('Sys')->getUserByName($uuid);

		if (!$obj->user) {
			$obj->uid = Model::factory("Sys")->register($uuid, $uname, $platform, $locale, $bundleid);
			if (!$obj->uid) exit('no user');
			//Util::SmallLog("mobile", "new:uid=".$obj->uid);
		} else {
			$obj->uid = $obj->user->uid;
		}
		if ($obj->uid < 100012) $obj->paramError();

		//$obj->user = $obj->login($obj->uid);
		$obj->user = CacheManager::getUser($obj->uid);
		if (!$obj->user) $obj->paramError();
		App::updateLoginData($obj->user, 1, $ver, $uuid, $network);

		$pkey = Util::makePkey($obj->uid, TIMESTAMP, $locale, 1);
		return $obj->jsonp(array('ret'=>0, 'login'=>0, 'user'=>self::getUser($obj->user, $vid), 'sessionKey'=>$pkey));
	}
	public static function Logout($obj) {
		$obj->checkData();

		Model::factory('App')->deleteOnline($obj->uid);
		$obj->jsonp(array('ret'=>0));
	}
	public static function Version($obj) {
		$obj->checkData();
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
	//投稿
	public static function Upload($obj){
		$obj->checkData();
		$text = $obj->req('text');
		if (!$text) return $obj->jsonp(array('ret'=>1, "msg" => $obj->lang['operator_failure']));
		$result = false;
		$admin = CacheManager::getAdmin($obj->uid);
		if ($admin) {
			$result = Model::factory('Setting')->insertJoke(array('joke'=>$text, 'ltime'=>TIMESTAMP, 'score'=>80));
		} else {
			$result = Model::factory('App')->insertUserJoke(array('uid'=>$obj->uid, 'joke'=>$text, 'ltime'=>TIMESTAMP));
		}
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
		$admin = CacheManager::getAdmin($user->uid);
		if ($admin) $user->admin = 1;

		return $user;
	}
	//更新用户登录数据
	private static function updateLoginData($user, $type = 0, $ver = '', $uuid = null, $network = null) {
		if (!$user) return false;
		$data = array();
		$data["login_times"] = $user->login_times + 1;
		$data['login_ip'] = IpLocation::getIP();
		$data['login_date'] = TIMESTAMP;

		Model::factory("Sys")->updateUser($user->uid, $data);
		CacheManager::removeUser($user->uid);

		if (!$uuid) $uuid = $user->uuid;

		Model::factory("App")->logUserLogin(array('uid'=>$user->uid, 'utype'=>$type, 'ldate'=>TIMESTAMP, 'ip'=>IpLocation::getIP(), 'ver'=>$ver?:'', 'uuid'=>$uuid?:'', 'network'=>$network?:''));
	}
}
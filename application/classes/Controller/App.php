<?php defined ( 'SYSPATH' ) or die ( 'No direct script access.' );

class Controller_App extends AppController {
	public $lang = null, $income = null, $locale = null, $user = null, $uid = 0, $platform = 'appstore', $mobile = false;

	public function __construct($request, $response) {
		parent::__construct ( $request, $response );
		$this->initApp($this);
	}

	public function paramError() { $this->jsonp(array("ret" => 4, "msg" => $this->lang['invalid_param'])); }
	public function loginError() { $this->jsonp(array("ret" => 1, "msg" => $this->lang['reset_login'], 'relogin' => 1)); }
	public function checkData() { if (!$this->income || $this->uid < 1 || !$this->user || !$this->lang) $this->loginError(); }
	public function errorKey($key, $code = 1) { $this->jsonp(array("ret" => $code, "msg" => $this->lang[$key])); }
	public function errorMsg($msg, $code = 1) { $this->jsonp(array("ret" => $code, "msg" => $msg)); }

	public function action_auth() { App::Auth($this); } //登录注册
	public function action_logout() { App::Logout($this); } //退出
	public function action_version() { App::Version($this); } //版本
	public function action_info() { App::Info($this); } //用户资料
	public function action_feedback() { App::Feedback($this); } //反馈
	public function action_search() { App::Search($this); } //搜索
	public function action_likes() { App::Likes($this); } //喜欢
	public function action_upload() { App::Upload($this); } //投稿

	public function action_joke() { App::Joke($this); } //赞笑话
	public function action_jokeLike() { App::JokeLike($this); } //赞笑话
	public function action_jokeShare() { App::JokeShare($this); } //分享笑话

	public function action_meitu() { App::Meitu($this); } //美图
	public function action_meituLike() { App::MeituLike($this); } //赞美图
	public function action_meituShare() { App::MeituShare($this); } //分享美图

}

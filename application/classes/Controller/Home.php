<?php defined ( 'SYSPATH' ) or die ( 'No direct script access.' );

class Controller_Home extends AppController {
	public function action_index(){
		$t = 0;
		$p = Util::getMobile();
		if ($p && $p['iphone']) $t = 1;
		if ($p && $p['ipad']) $t = 1;
		if ($p && $p['android']) $t = 2;
		if ($t == 0 || Util::isCrawler()){
			$data = Model::factory('Setting')->getJokes(0, 0, 0, '', 1, 200);
			echo View::factory('crawler', array('jokes'=>$data));
		} else {
			if (Util::isWeixinOpen())
				echo View::factory('down', array('t'=>$t));
			else
				echo View::factory('index', array('t'=>$t));
		}
	}
	public function action_joke(){
		$id = intval($this->request->param('id'));
		$joke = Model::factory('Setting')->getJoke($id);
		$data = Model::factory('Setting')->getJokeMore($id, 50);
		echo View::factory('joke', array('jokes'=>$data, 'joke'=>$joke));
	}
}

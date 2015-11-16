<?php defined ( 'SYSPATH' ) or die ( 'No direct script access.' );

class Controller_Home extends AppController {
	public function action_index(){
		$t = 0;
		$p = Util::getMobile();
		if ($p && $p['iphone']) $t = 1;
		if ($p && $p['ipad']) $t = 1;
		if ($p && $p['android']) $t = 2;
		if (Util::isWeixinOpen())
			echo View::factory('down', array('t'=>$t));
		else
			echo View::factory('index', array('t'=>$t));
	}
}

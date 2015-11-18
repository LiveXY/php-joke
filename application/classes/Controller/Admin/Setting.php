<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Setting extends AdminController {
	//美图
	public function action_meitu_list() {
		$this->checkFunction("MeituManage");

		$tid = intval($this->request->query('tid'));
		if ($tid < 1) $tid = 0;

		$DATA = array();
		$DATA['user_right'] = $this->funcOp('MeituManage');
		$DATA['list'] = Model::factory('Setting')->getJokes(1, $tid, 0, '', 1, 20);
		$DATA['tags'] = CacheManager::getTags();
		$DATA['tid'] = $tid;

		View::set_global('title', '美图');
		echo $this->iframeView('admin/setting/meitu_list', $DATA);
	}
	public function action_meitu_more() {
		$tid = intval($this->request->post('tid'));
		if ($tid < 1) $tid = 0;
		$page = intval($this->request->post('page'));
		if ($page < 1) $page = 1;
		$list = Model::factory('Setting')->getJokes(1, $tid, 0, '', $page, 20);
		$data = array('status'=>1, 'list'=>array());
		foreach($list as $info) $data['list'][] = $info;
		return $this->json($data);
	}
	public function action_check_meitu(){
		$data = array('status'=>0);
		$files = $this->request->post('file');
		$tags = trim($this->request->post('tags'), ';');
		if (empty($files) || !is_array($files) || count($files)==0) return $this->json($data);
		foreach($files as $file) {
			if (!is_bool(strpos($file, '/'))) return $this->json($data);
			if (!is_bool(strpos($file, '\\'))) return $this->json($data);
		}

		foreach($files as $file) {
			$source = RESOURCE_PATH.'upload/temp/'.$file;
			$srcfile = "upload/meitu/".$file;
			$srcfile_path = RESOURCE_PATH.$srcfile;
			if (!file_exists(dirname($srcfile_path))) mkdir(dirname($srcfile_path), 0777, true);
			if (file_exists($source)) rename($source, $srcfile_path);

			$thumbfile = "upload/meitu/thumb-".$file;
			$thumbfile_path = RESOURCE_PATH.$thumbfile;
			if (file_exists($srcfile_path)) Util::photoThumb($srcfile_path, $thumbfile_path, 400, 400, 80);

			$s = Util::photoSize($srcfile_path);
			$meitu = array('tags'=>$tags, 'img'=>$file, 'type'=>1, 'ltime'=>TIMESTAMP, 'width'=>$s['w'], 'height'=>$s['h']);
			$result = Model::factory('Setting')->insertJoke($meitu);
			if ($result) {
				$data = array(); $tags = explode(';', $tags);
				foreach ($tags as $tid) {
					if ($tid < 1) continue;
					$data[] = array('jid'=>$id, 'tid'=>$tid);
				}
				Model::factory('Setting')->insertJokeTags($data);
			}
		}

		$data['status'] = 1;
		return $this->json($data);
	}
	public function action_meitu_edit(){
		$jid = intval($this->request->post('jid'));
		$tags = trim($this->request->post('tags'),';');
		if ($jid<1 || empty($tags)) return $this->json(0);

		$result = Model::factory('Sns')->updateJoke($jid, array('tags'=>$tags));
		if ($result) {
			Model::factory('Setting')->deleteJokeTags($jid);
			$data = array(); $tags = explode(';', $tags);
			foreach ($tags as $tid) {
				if ($tid < 1) continue;
				$data[] = array('jid'=>$jid, 'tid'=>$tid);
			}
			Model::factory('Setting')->insertJokeTags($data);
		}

		return $this->json($result ? 1 : 0);
	}
	public function action_meitu_delete(){
		$jid = intval($this->request->post('jid'));
		if ($jid < 1) return $this->json(0);

		$info = Model::factory('Sns')->getJoke($jid);
		if ($info) {
			$file = $info->img;
			if (!is_bool(strpos($file, '/'))) return $this->json(0);
			if (!is_bool(strpos($file, '\\'))) return $this->json(0);

			$filename = RESOURCE_PATH.'upload/meitu/'.$file;
			if(file_exists($filename)) unlink($filename);
			$filename = RESOURCE_PATH.'upload/meitu/thumb-'.$file;
			if(file_exists($filename)) unlink($filename);

			Model::factory('Setting')->deleteJoke($jid);
			Model::factory('Setting')->deleteJokeTags($jid);
		}

		return $this->json(1);
	}
	//笑话标签管理
	public function action_tag_list() {
		$this->checkFunction("TagManage");

		$DATA = array();
		$DATA['list']			= CacheManager::getTags();
		$DATA['user_right'] 	= $this->funcOp('TagManage');

		View::set_global('title', '管理');
		echo $this->iframeView('admin/setting/tag_list', $DATA);
	}
	public function action_tag_op(){
		$id 	= intval($this->request->query('id'));
		$list	= CacheManager::getTags();

		$DATA['id'] 			= $id;
		$DATA['info'] 			= isset($list[$id]) ? $list[$id] : false;
		$DATA['user_right'] 	= $this->funcOp('TagManage');

		View::set_global('title', '操作');
		echo $this->iframeView('admin/setting/tag_op', $DATA);
	}
	public function action_tag_post(){
		$id 	= intval($this->request->query('id'));

		$txtTitle				= $this->request->post('txtTitle');
		$txtOrder				= intval($this->request->post('txtOrder'));

		$data = array(
			'title'				=> $txtTitle,
			'orderby'			=> $txtOrder,
		);

		if ($id < 0) { //添加
			$this->checkFunction('TagManage', "add");

			Model::factory('Setting')->insertTags($data);
		} else { //修改
			$this->checkFunction('TagManage', "edit");

			Model::factory('Setting')->updateTags($id, $data);
		}
		CacheManager::removeTags();
		return $this->redirect("admin/setting/tag_list");
	}
	public function action_tag_delete(){
		$this->checkFunction('TagManage', "delete");

		$id 	= intval($this->request->query('id'));
		if ($id < 1) $this->paramError();

		Model::factory('Setting')->deleteTags($id);
		CacheManager::removeTags();
		return $this->redirect('admin/setting/tag_list');
	}
	//笑话管理
	public function action_joke_list() {
		$this->checkFunction("JokeManage");
		$page = intval($this->req('page'));
		if ($page < 1) $page = 1;
		$tid = intval($this->req('tid'));
		if ($tid < 1) $tid = 0;
		$key = $this->req('key');

		$DATA = array();
		$DATA['page']			= $page;
		$DATA['tid']			= $tid;
		$DATA['key']			= $key;
		$DATA['list']			= Model::factory('Setting')->getJokes(0, $tid, 0, $key, $page, 25);
		$DATA['totals']			= Model::factory('Setting')->getJokeCount(0, $tid, 0, $key);
		$DATA['user_right'] 	= $this->funcOp('JokeManage');
		$DATA['tags']			= CacheManager::getTags();

		View::set_global('title', '管理');
		echo $this->iframeView('admin/setting/joke_list', $DATA);
	}
	public function action_joke_op(){
		$id 	= intval($this->request->query('id'));
		$info	= Model::factory('Setting')->getJoke($id);

		$DATA['id'] 			= $id;
		$DATA['info'] 			= $info;
		$DATA['user_right'] 	= $this->funcOp('JokeManage');
		$DATA['post']			= intval($this->request->query('post'));
		$DATA['tags']			= CacheManager::getTags();

		View::set_global('title', '操作');
		echo $this->iframeView('admin/setting/joke_op', $DATA);
	}
	public function action_joke_post(){
		$id 	= intval($this->request->query('id'));

		$txtTitle				= $this->request->post('txtTitle');
		$txtJoke				= $this->request->post('txtJoke');
		$txtTags 				= trim($this->request->post('txtTags'),';');
		$txtScore				= intval($this->request->post('txtScore'));

		$data = array(
			'title'				=> $txtTitle,
			'joke'				=> $txtJoke,
			'tags'				=> $txtTags,
			'score'				=> $txtScore,
		);
		$result = false;

		if ($id <= 0) { //添加
			$data['ltime'] = TIMESTAMP;
			$this->checkFunction('JokeManage', "add");

			$result = Model::factory('Setting')->insertJoke($data);
			$id = $result[0];
		} else { //修改
			$this->checkFunction('JokeManage', "edit");

			$result = Model::factory('Setting')->updateJoke($id, $data);
		}
		if ($result) {
			Model::factory('Setting')->deleteJokeTags($id);
			$data = array(); $tags = explode(';', $txtTags);
			foreach ($tags as $tid) {
				if ($tid < 1) continue;
				$data[] = array('jid'=>$id, 'tid'=>$tid);
			}
			Model::factory('Setting')->insertJokeTags($data);
		}
		return $this->redirect("admin/setting/joke_op?post=1&id=$id");
	}
	public function action_joke_delete(){
		$this->checkFunction('JokeManage', "delete");
		$page = intval($this->req('page'));
		if ($page < 1) $page = 1;
		$key = $this->req('key');

		$id 	= intval($this->request->query('id'));
		if ($id < 1) $this->paramError();

		$result = Model::factory('Setting')->deleteJoke($id);
		if ($result) Model::factory('Setting')->deleteJokeTags($id);
		return $this->redirect("admin/setting/joke_list?tid=$tid&key=$key&page=$page");
	}
	public function action_index(){}
	//笑话审核
	public function action_audit_list() {
		$this->checkFunction("AuditJoke");
		$page = intval($this->req('page'));
		if ($page < 1) $page = 1;
		$key = $this->req('key');

		$DATA = array();
		$DATA['page']			= $page;
		$DATA['key']			= $key;
		$DATA['list']			= Model::factory('Setting')->getAuditList($key, $page, 25);
		$DATA['totals']			= Model::factory('Setting')->getAuditCount($key);
		$DATA['user_right'] 	= $this->funcOp('AuditJoke');

		View::set_global('title', '管理');
		echo $this->iframeView('admin/setting/audit_list', $DATA);
	}
	public function action_audit_op(){
		$id 	= intval($this->request->query('id'));
		$info	= Model::factory('Setting')->getUserJoke($id);

		$DATA['id'] 			= $id;
		$DATA['info'] 			= $info;
		$DATA['user_right'] 	= $this->funcOp('AuditJoke');
		$DATA['post']			= intval($this->request->query('post'));
		$DATA['tags']			= CacheManager::getTags();

		View::set_global('title', '操作');
		echo $this->iframeView('admin/setting/audit_op', $DATA);
	}
	public function action_audit_post(){
		$id 	= intval($this->request->query('id'));
		if ($id < 1) $this->paramError();

		$txtTitle				= $this->request->post('txtTitle');
		$txtJoke				= $this->request->post('txtJoke');
		$txtTags 				= trim($this->request->post('txtTags'),';');
		$txtScore				= intval($this->request->post('txtScore'));

		$data = array(
			'title'				=> $txtTitle,
			'joke'				=> $txtJoke,
			'tags'				=> $txtTags,
			'score'				=> $txtScore,
			'ltime'				=> TIMESTAMP
		);

		$this->checkFunction('JokeManage', "add");
		$result = Model::factory('Setting')->insertJoke($data);

		if ($result) {
			$jid = $result[0];
			Model::factory('Setting')->deleteUserJoke($id);
			Model::factory('Setting')->deleteJokeTags($id);
			$data = array(); $tags = explode(';', $txtTags);
			foreach ($tags as $tid) {
				if ($tid < 1) continue;
				$data[] = array('jid'=>$jid, 'tid'=>$tid);
			}
			Model::factory('Setting')->insertJokeTags($data);
		}
		echo "<script>alert('审核成功！');top.tab.refresh('JokeManage', true);top.tab.refresh('AuditJoke', true);top.tab.close();</script>";
	}
	public function action_audit_delete(){
		$this->checkFunction('AuditJoke', "delete");
		$page = intval($this->req('page'));
		if ($page < 1) $page = 1;
		$key = $this->req('key');

		$id 	= intval($this->request->query('id'));
		if ($id < 1) $this->paramError();

		$result = Model::factory('Setting')->deleteUserJoke($id);
		return $this->redirect("admin/setting/audit_list?key=$key&page=$page");
	}
	//缓存管理
	public function action_cache_list() {
		$this->checkFunction("CacheManage");

		View::set_global('title', '管理');
		echo $this->iframeView('admin/setting/cache_list', array());
	}
	//用户反馈管理
	public function action_feedback_list() {
		$page = intval($this->request->query('page'));
		if ($page < 1) $page = 1;
		$this->checkFunction("FeedbackManage");

		$DATA = array();
		$DATA['page'] 			= $page;
		$DATA['list']			= Model::factory('Setting')->getFeedbackList($page);
		$DATA['totals']			= Model::factory('Setting')->getFeedbackCount();
		$DATA['user_right'] 	= $this->funcOp('FeedbackManage');

		View::set_global('title', '管理');
		echo $this->iframeView('admin/setting/feedback_list', $DATA);
	}
	public function action_feedback_delete(){
		$this->checkFunction('FeedbackManage', "delete");

		$id 	= intval($this->request->query('id'));
		$page 	= intval($this->request->query('page'));
		if ($id < 1) $this->paramError();

		Model::factory('Setting')->deleteFeedback($id);
		return $this->redirect('admin/setting/feedback_list?page='.$page);
	}
}
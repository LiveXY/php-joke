<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Api_Upload extends Controller {
	public function action_meitu(){
		$inputName = 'Filedata';
		$mtime = explode(' ', microtime());
		$timestamp = $mtime[1];

		$json = array('err'=>'', 'msg'=>'');

		if(!$_FILES[$inputName] || $_FILES[$inputName]['error'] != 0 || !$_FILES[$inputName]['name']) {
			$json['err'] = '上传失败，請選擇图片0！';
			return $this->json($json);
		}
		if($_FILES[$inputName]['size'] > 3*1024*1024) {
			$json['err'] = '上传失败，文件大于5M！';
			return $this->json($json);
		}
		$filename = $_FILES[$inputName]['name'];
		$file_info = getimagesize($_FILES[$inputName]['tmp_name']);

		if($file_info['mime'] != 'image/jpeg' and $file_info['mime'] != 'image/png' and $file_info['mime'] != 'image/gif') {
			$json['err'] = '上传失败，文件類型错误！';
			return $this->json($json);
		}

		if(!is_uploaded_file($_FILES[$inputName]['tmp_name'])) {
			$json['err'] = '上传失败，請選擇图片1！';
			return $this->json($json);
		}

		if($file_info['mime'] == 'image/jpeg') {
			$ext = 'jpg';
		} elseif ($file_info['mime'] == 'image/gif') {
			$ext = 'gif';
		} else {
			$ext = 'png';
		}

		$srcfile = "upload/temp/".uniqid().'.'.$ext;
		$srcfile_path = RESOURCE_PATH.$srcfile;
		if (file_exists($srcfile_path)) {
			$json['err'] = '上传失败，文件已存在！';
			return $this->json($json);
		}
		if (!file_exists(dirname($srcfile_path))) mkdir(dirname($srcfile_path), 0777, true);

		if(move_uploaded_file($_FILES[$inputName]['tmp_name'], $srcfile_path)) {
			$json['msg'] = $srcfile;
			return $this->json($json);
		}

		$json['err'] = '上传失败，請選擇图片2！';
		return $this->json($json);
	}
	public function action_zip() {
		$inputName = 'Filedata';
		$json = array('err'=>'', 'msg'=>'');

		$v = $this->req('v');
		if (empty($v)) {
			$json['err'] = '上传失败，版本号错误！';
			return $this->json($json);
		}

		if(!$_FILES[$inputName] || $_FILES[$inputName]['error'] != 0 || !$_FILES[$inputName]['name']) {
			$json['err'] = '上传失败0！';
			return $this->json($json);
		}
		if($_FILES[$inputName]['size'] > 5*1024*1024) {
			$json['err'] = '上传失败，文件大于5Ｍ！';
			return $this->json($json);
		}

		if(!is_uploaded_file($_FILES[$inputName]['tmp_name'])) {
			$json['err'] = '上传失败1！';
			return $this->json($json);
		}

		$filename = $v.'.zip';
		$srcfile = "upload/version/".$filename;
		$srcfile_path = RESOURCE_PATH.$srcfile;
		if (file_exists($srcfile_path)) unlink($srcfile_path);
		if (!file_exists(dirname($srcfile_path))) mkdir(dirname($srcfile_path), 0777, true);

		if(move_uploaded_file($_FILES[$inputName]['tmp_name'], $srcfile_path)) {
			$json['msg'] = $srcfile;
			return $this->json($json);
		}

		$json['err'] = '上传失败2！';
		return $this->json($json);
	}
}
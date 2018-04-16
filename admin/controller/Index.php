<?php
/**
 * Created by PhpStorm.
 * User: gzl
 * Date: 2018/3/8
 * Time: 上午9:28
 */
namespace app\admin\controller;

class Index extends Base{
	
	
	public function index(){
		return $this->fetch();
	}
	
	public function welcome(){
		return $this->fetch();
	}
	
	public function get(){
		return $this->fetch();
	}
	
	public function getFile(){
		$file = $this->request->file();
		$arr = qnUpload($file);
		dump($arr);die;
		
	}
}
<?php
/**
 * Created by PhpStorm.
 * User: gzl
 * Date: 2018/4/14
 * Time: 下午3:42
 */
namespace app\admin\controller;

class Auth extends Base{
	public function authEdit(){
		$post = $this->request->post();
		dump($post);die;
	}
	
}
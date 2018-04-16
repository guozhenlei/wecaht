<?php
/**
 * Created by PhpStorm.
 * User: gzl
 * Date: 2018/3/7
 * Time: 上午10:21
 */
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Admin as AdminModel;

class Login extends Controller{
	
	public function get(){
		echo time();
	}
	
	public function login(){
		$res = [
			'userName' => cookie('userName'),
			'password' => cookie('password')
		];
		$this->assign('res', $res);
		return $this->fetch();
	}
	
	/**
	 * 验证密码
	 * @return string
	 * @throws \think\db\exception\DataNotFoundException
	 * @throws \think\db\exception\ModelNotFoundException
	 * @throws \think\exception\DbException
	 */
	public function logAjax(){
		$post = $this->request->post();
		$exit = AdminModel::where('userName', $post['userName'])->find();
		if(empty($exit)){
			return json_code('error', '404', '用户不存在');
		}
		$user = AdminModel::where(['userName' => $post['userName'],'password' => md5(md5($post['password']).$exit['randNumber'])])->find();
		if(empty($user)){
			return json_code('error', '404', '用户账号或密码错误');
		}
		if(isset($post['rememberMe'])){
			cookie('userName', $post['userName']);
			cookie('password', $post['password']);
		}
		session('adminName', $user['userName']);
		session('adminId', $user['id']);
		return json_code('ok', '200', '登录成功');
	}
}

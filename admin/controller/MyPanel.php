<?php
/**
 * Created by PhpStorm.
 * User: gzl
 * Date: 2018/4/4
 * Time: 下午3:21
 */
namespace app\admin\controller;

class MyPanel extends Base{
	//用户信息
	public function userInformation(){
		return $this->fetch();
	}
}
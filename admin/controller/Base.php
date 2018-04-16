<?php
/**
 * Created by PhpStorm.
 * User: gzl
 * Date: 2018/3/22
 * Time: 上午11:20
 */
namespace app\admin\Controller;

use think\Controller;
use think\facade\Env;


class Base extends Controller{
	
	public function _initialize(){
		$adminName = session('adminName');
		$adminId = session('adminId');
		if(empty($adminName) || empty($adminId)){
			$this->success('过期','admin/login/login');
		}
	}
	
	
	public function up_img(){
		$img = Request()->file();
		$info = $img->move(Env::get('root_path'.'/public/upload'));
		if($info){
			echo $info->getFilename();
		}else{
			echo $info->getError();
		}
	}
}
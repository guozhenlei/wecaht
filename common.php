<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

use Qiniu\Auth; //鉴权类
use Qiniu\Storage\UploadManager; //上传类
use think\facade\Env;
require Env::get('root_path').'vendor/autoload.php'; //引用 七牛云 SDK

/*require Env::get('root_path').'vendor/phpoffice/phpexcel/Classes/PHPExcel.php'; //PHPExcel核心类
require Env::get('root_path').'vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php'; //用于保存文件的类*/

// 应用公共文件
function json_code($msg, $code, $data){
	if(!empty($msg) && !empty($code)){
		return json_encode(['msg' => $msg, 'code' => $code, 'data' => $data]);
	}
}




//获取配置项
function getReset($key){
	if($key){
		return \think\Db::table('wechat_web_setting')->where('key', $key)->value('value');
	}
	return false;
}

//七牛云上传
function qnUpload($file){
	$accessKey = getReset('access_key');
	$secretKey = getReset('access_secret');
	$bucket = getReset('space_name'); //空间域名
	$domain = getReset('bind_domain'); //绑定域名
	
	$auth = new Auth($accessKey, $secretKey);
	$token = $auth -> uploadToken($bucket);
	// 初始化 UploadManager 对象并进行文件的上传。
	$uploadMgr = new UploadManager();
	
	foreach($file as $k => $v){
		$filePath[$k] = $file[$k] -> getRealPath();
		$ext[$k] = pathinfo($file[$k] -> getInfo('name'), PATHINFO_EXTENSION);
		$key[$k] = date('YmdHis').random(14).'.'.$ext[$k];
		$upload[$k] = $uploadMgr -> putFile($token, $key[$k], $filePath[$k]);
		if($upload[$k][1] !== null){
			throw new Exception('文件'.$k.'上传错误,'.$upload[$k][1]);
		}
		$url[$k] = 'http://'.$domain.'/'.$upload[$k][0]['key'];
		return $url[$k];
	}
	
}
	/*else{
		// 要上传图片的本地路径
		$filePath = $file->getRealPath();
		$ext = pathinfo($file->getInfo('name'), PATHINFO_EXTENSION);  //后缀
		// 上传到七牛后保存的文件名
		$key =date('YmdHis') . random(14) . '.' . $ext;
		
		$url = $uploadMgr->putFile($token, $key, $filePath);
		if($url[1] == null){
			return $domain.'/'.$url[0]['key'];
		}
	}
	//dump($filePath).'\n'.dump($ext).'\n'.dump($key).'\n'.dump($upload).'\n'.dump($url);die;
	// 要上传图片的本地路径
	$filePath = $file->getRealPath();
	$ext = pathinfo($file->getInfo('name'), PATHINFO_EXTENSION);  //后缀
	// 上传到七牛后保存的文件名
	$key =date('YmdHis') . random(14) . '.' . $ext;
	
	// 调用 UploadManager 的 putFile 方法进行文件的上传。
	list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
	if ($err == null) {
		return $domain.'/'.$ret['key'];
	} else {
		return $err;
	}*/
	
	
/*function phpExcel(){
	$objPHPExcel = new PHPExcel();
	$objSheet = $objPHPExcel->getActiveSheet();    //获取当前shhet
	$objSheet->setTitle('我的sheet名称');           //设置sheet名称
	$objSheet->setCellValueByColumnAndRow(3,4,'name');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
	
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="下载的文件名"');
	header('Cache-Control: max-age=0');
	
	$objWriter->save('php://output');
}*/


//随机字符串
function random($len = 14){
	$data ='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$str = '';
	for ($a = 0; $a < $len; $a++) {
		$str .= $data[mt_rand(0, 25)];    //生成php随机数
	}
	return $str;
}


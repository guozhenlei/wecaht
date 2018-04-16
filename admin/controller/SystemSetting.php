<?php
/**
 * Created by PhpStorm.
 * User: gzl
 * Date: 2018/3/13
 * Time: 下午3:59
 */

namespace app\admin\controller;

use \app\admin\model\SystemSetting as SysModel;
use \app\admin\model\PaySetting as PayModle;

class SystemSetting extends Base{
	//基础设置
	public function basic(){
		$data = ['webName', 'webAdminName', 'record', 'webTitle', 'webDesc', 'webKey', 'webLog'];
		foreach($data as $k => $v){
			$res[$v] = SysModel::getSetting($v);
		}
		$this->assign('res', $res);
		return $this -> fetch();
	}
	
	//添加基础设置
	public function basic_edit(){
		if($this->request->isPost()){
			$post = $this -> request -> post();
			$data = [
				"webName" => $post['webName'],
				"webAdminName" => $post['webAdminName'],
				"record" => $post['record'],
				"webTitle" => $post['webTitle'],
				"webLog" => $post['webLog'],
			];
			
			//数据验证
			$result = $this->validate($data, 'SystemSetting.basic_edit');
			if($result !== true){
				return json_code('error', '404', $result);
			}
			$val = [
				'webDesc' => $post['webDesc'],
				'webKey' => $post['webKey'],
				'webLog' => $post['webLog'],
			];
			$web = array_merge($data, $val);
			foreach($web as $key=>$val){
				switch($key){
					case 'webName':
						$name = '网站名称';
						break;
					case 'webAdminName':
						$name = '后台系统名';
						break;
					case 'record':
						$name = '备案号';
						break;
					case 'webTitle':
						$name = '网站标题';
						break;
					//case 'webLog':
						//$name = '网站Log';
						//break;
					case 'webDesc':
						$name = '网站描述';
						break;
					case 'webKey':
						$name = '网站关键字';
						break;
					default:
						$name = '';
						break;
				}
				try{
					SysModel::updateSetting($key, $val, $name);
				}catch (\Exception $ex){
					return json_code('error', '404', $ex->getMessage());
				}
			}
			return json_code('ok', '200', '添加成功');
		}
		
	}
	
	//短信设置
	public function sms(){
		$data = ['sms_key', 'sms_secret', 'sms_sign', 'sms_tem', 'con_var'];
		foreach($data as $k => $v){
			$res[$v] = SysModel::getSetting($v);
		}
		$this->assign('res', $res);
		return $this -> fetch();
	}
	
	/*
	 * 短信编辑
	 */
	public function sms_edit(){
		if($this->request->isPost()){
			$post = $this->request->post();
			$res = $this->validate($post, 'SystemSetting.sms_edit');
			if($res !== true){
				return json_code('error', '404', $res);
			}
			foreach($post as $key=>$val){
				switch($key){
					case 'sms_key':
						$name = '短信Key';
						break;
					case 'sms_secret':
						$name = '短信Secret';
						break;
					case 'sms_sign':
						$name = '短信签名';
						break;
					case 'sms_tem':
						$name = '短信模版';
						break;
					case 'con_var':
						$name = '内容变量';
						break;
				}
				SysModel::updateSetting($key, $val, $name);
			}
			return json_code('ok', '200', '添加成功');
		}
	}
	
	//邮件设置
	public function email(){
		$data = ['server_smtp', 'server_port', 'send_user', 'password'];
		foreach($data as $k => $v){
			$res[$v] = SysModel::getSetting($v);
		}
		$this->assign('res', $res);
		return $this -> fetch();
	}
	
	/*
	 * 邮箱编辑
	 */
	public function email_edit(){
		if($this->request->isPost()){
			$post = $this->request->post();
			$res = $this->validate($post, 'SystemSetting.email_edit');
			if($res !== true){
				return json_code('error', '404', $res);
			}
			foreach($post as $key=>$val){
				switch($key){
					case 'server_smtp':
						$name = '服务器地址';
						break;
					case 'server_port':
						$name = '服务器端口号';
						break;
					case 'send_user':
						$name = '发送方账号';
						break;
					case 'password':
						$name = '密码';
						break;
				}
				SysModel::updateSetting($key, $val, $name);
			}
			return json_code('ok', '200', '添加成功');
		}
	}
	
	//支付列表
	public function pay(){
		return $this->fetch();
	}
	
	//支付列表
	public function payList(){
		return model('PaySetting')->payList();
	}
	
	/*
	 * 支付编辑
	 */
	public function payEdit(){
		//编辑
		if($this->request->isPost()){
			$post = $this->request->post();
			if(isset($post['alipay_app_id'])){
				$res = $this->validate($post, 'SystemSetting.alipay_edit');
			}else{
				$res = $this->validate($post, 'SystemSetting.wechat_edit');
			}
			if($res !== true){
				return json_code('error', '404', $res);
			}
			foreach($post as $key=>$val){
				switch($key){
					case 'alipay_app_id':
						$name = '支付宝AppId';
						break;
					case 'alipay_notify_url':
						$name = '支付宝回调地址';
						break;
					case 'alipay_public_key':
						$name = '支付宝公钥';
						break;
					case 'merchant_public_key':
						$name = '商户公钥';
						break;
					case 'merchant_private_key':
						$name = '商户私钥';
						break;
					case 'wechat_appid':
						$name = '微信AppId';
						break;
					case 'wechat_app_id':
						$name = '微信公众号AppId';
						break;
					case 'wechat_miniapp_id':
						$name = '微信小程序AppId';
						break;
					case 'wechat_mch_id':
						$name = '微信商户号Id';
						break;
					case 'wechat_key':
						$name = '微信Key';
						break;
					case 'wechat_notify_url':
						$name = '微信回调地址';
						break;
				}
				SysModel::updateSetting($key, $val, $name);
			}
			return json_code('ok', '200', '添加成功');
		}else{
			//查看
			$id = $this->request->get('id');
			$key = PayModle::getPayKey($id);
			if($key == 'zfb_pay'){
				$data = ['alipay_app_id', 'alipay_notify_url', 'alipay_public_key', 'merchant_public_key', 'merchant_private_key'];
				foreach($data as $k => $v){
					$res[$v] = SysModel::getSetting($v);
				}
			}else{
				$data = ['wechat_appid', 'wechat_app_id', 'wechat_miniapp_id', 'wechat_mch_id', 'wechat_key', 'wechat_notify_url'];
				foreach($data as $k => $v){
					$res[$v] = SysModel::getSetting($v);
				}
			}
			$this->assign('key', $key);
			$this->assign('res', $res);
			return $this->fetch();
		}
	}
	
	/*
	 * 删除支付方式
	 */
	public function payDel(){
		if($this->request->isPost()){
			$id = $this->request->post('id');
			if(PayModle::payDel($id)){
				return json_code('ok', '200', '删除成功');
			}
			return json_code('error', '404', '删除失败');
		}
		return json_code('error', '404', '请求方式错误');
	}
	
	/*
	 * 更改支付开启状态
	 */
	public function uploadIsOpen(){
		if($this->request->isPost()){
			$id = $this->request->post('id');
			if(PayModle::isOpen($id)){
				return json_code('ok', '200', '成功');
			}
			return json_code('error', '404', '失败');
		}
		return json_code('error', '404', '请求方式错误');
	}
	
	//上传设置
	public function upload(){
		$data = ['access_key', 'access_secret', 'space_name', 'bind_domain'];
		foreach($data as $k => $v){
			$res[$v] = SysModel::getSetting($v);
		}
		$this->assign('res', $res);
		return $this -> fetch();
	}
	
	/*
	 * 上传编辑
	 */
	public function upload_edit(){
		if($this->request->isPost()){
			$post = $this->request->post();
			$res = $this->validate($post, 'SystemSetting.upload_edit');
			if($res !== true){
				return json_code('error', '404', $res);
			}
			foreach($post as $key=>$val){
				switch($key){
					case 'access_key':
						$name = '七牛云Key';
						break;
					case 'access_secret':
						$name = '七牛云Secret';
						break;
					case 'space_name':
						$name = '空间名称';
						break;
					case 'bind_domain':
						$name = '七牛云绑定域名';
						break;
				}
				SysModel::updateSetting($key, $val, $name);
			}
			return json_code('ok', '200', '添加成功');
		}
	}
	
	//上传图片
	public function upImg(){
		$file = $this->request->file();
		$arr = qnUpload($file);
		return json(['msg' => 'ok', 'code' => '200', 'data' => $arr]);
		
	}
	
	public function get123(){
		return json_code('123', '123', '123');
	}
	
	public function name(){
		return $this->fetch();
	}
}
<?php
/**
 * Created by PhpStorm.
 * User: gzl
 * Date: 2018/3/8
 * Time: 上午11:41
 */
namespace app\index\controller;


use think\Controller;

class Base extends controller{
	protected $appId;
	protected $secret;
	//回复消息模版
	protected $template = [
		'text' => "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					</xml>",
	];
	
	public function __construct(){
		$this->appId = config('app.WeChat.appId');
		$this->secret = config('app.WeChat.secret');
	}
	
	//第一步配置服务器  get请求
	/*public function checkSignature(){
		$echostr = $this->request->get('echostr');
		if($this->checkSign()){
			echo $echostr;
		}else{
			echo "微信验证不通过";
		}
		
	}*/
	
	//第二步 设置关注者事件   与微信服务器交互地址
	public function checkSignature(){
		if(!$this->checkSign()){
			echo '验证不通过';
		}
		$this->responseMsg();
	}
	
	//获取网页授权access_token and openid  返回数组
	public function get_web_token(){
		$appid = "wx46685458f08e430c";
		$service = "7f570ba366ae45f999efe84f7b91480b";
		if(!empty($_GET['code'])){
			$code = $_GET['code'];
			$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$service&code=$code&grant_type=authorization_code";
			$access_token = $this->httpRequest($url);
			$access_token = json_decode($access_token, true);
			dump($access_token);die;
			//$user_id = db('user')->where('wechat_openid', $access_token['openid'])->value('user_id');
			//Session::set('user_id', $user_id);
		}else{
			$redirect_url = "http://wechat.guozhenlei.top/index.php/index/base/get_web_token";
			$redirect_url = rtrim($redirect_url, '/');
			$redirect_url = urlencode($redirect_url);
			$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_url&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
			$this->redirect($url);
		}
	}
	
	//自定义消息回复 + 关注事件'
	public function responseMsg(){
		//获取微信返回的数据
		$postStr = file_get_contents("php://input");
		if(!empty($postStr)){
			libxml_disable_entity_loader(true); //防止XXE攻击
			$postObj = simplexml_load_string($postStr); //xml字符串转化为对象
			$toUser = $postObj -> ToUserName; //开发者微信号
			$fromUsername = $postObj -> FromUserName; //发送方账号 openid
			$time = time();
			switch(strtolower($postObj -> MsgType)){ //关注事件的类型 转化为小写
				case 'event':
					$msgType = 'text'; //回复类型统一为文本类型
					if(strtolower($postObj -> Event) == 'subscribe'){
						$accessToekn = $this->accessToken();
						$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$accessToekn&openid=$fromUsername&lang=zh_CN";
						$result = $this->httpRequest($url);
						$result = json_decode($result, true);
						$content = '你好'.$result['nickname'].',欢迎关注我的公众号，你好呀，我叫赛利亚！';
						$info = sprintf($this->template['text'], $fromUsername, $toUser, $time, $msgType, $content);
						echo $info;
					}
					break;
				case 'image': //图片
					$msgType = "text";
					$contentStr = "您发送的是图片消息！";
					$resultStr = sprintf($this->template['text'], $fromUsername, $toUser, $time, $msgType, $contentStr);
					echo $resultStr;
					break;
				case 'voice': //语音
					$msgType = "text";
					$contentStr = "您发送的是语音消息！";
					$resultStr = sprintf($this->template['text'], $fromUsername, $toUser, $time, $msgType, $contentStr);
					echo $resultStr;
					break;
				case 'video': //视频
					$msgType = "text";
					$contentStr = "您发送的是视频消息！";
					$resultStr = sprintf($this->template['text'], $fromUsername, $toUser, $time, $msgType, $contentStr);
					echo $resultStr;
					break;
				case 'shortvideo': //小视频
					$msgType = "text";
					$contentStr = "您发送的是小视频消息！";
					$resultStr = sprintf($this->template['text'], $fromUsername, $toUser, $time, $msgType, $contentStr);
					echo $resultStr;
					break;
				case 'location': //地理位置
					$msgType = "text";
					$contentStr = "您发送的是地理位置消息！";
					$resultStr = sprintf($this->template['text'], $fromUsername, $toUser, $time, $msgType, $contentStr);
					echo $resultStr;
					break;
				case 'link': //链接
					$msgType = "text";
					$contentStr = "您发送的是链接消息！";
					$resultStr = sprintf($this->template['text'], $fromUsername, $toUser, $time, $msgType, $contentStr);
					echo $resultStr;
					break;
			}
		}else{
			echo '';
			exit;
		}
	}
	
	//自定义菜单创建接口
	public function customMenuCreation(){
		header('content-type:text/html;charset=utf-8');
		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->accessToken();
		$data = [
			'button' => [
				[
					'name' => urlencode('我的博客'),
					'type' => 'view',
					'url' => 'https://hd.faisco.cn/16459472/30A5kcShgejxop-fkWLFCw/load.html?style=2',
				],
				[
					'name' => urlencode('我的博客'),
					'sub_button' => [
						[
							'name' => urlencode('我的博客'),
							'type' => 'view',
							'url' => 'http://blog.guozhenlei.top',
						],
						[
							'name' => urlencode('我的博客'),
							'type' => 'view',
							'url' => 'http://blog.guozhenlei.top',
						],
					]
				],
				[
					'name' => urlencode('我的博客'),
					'type' => 'view',
					'url' => 'http://blog.guozhenlei.top',
				],
			],
		];
		$data = urldecode(json_encode($data));
		$menu = json_decode($this->httpRequest($url, $data));
		if($menu->errcode != '0' || $menu->errmsg != 'ok'){
			die('菜单创建失败');
		}
		die('菜单创建成功');
		
	}
	
	//自定义菜单查询接口
	public function customMenuQuery(){
		$url = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token='.$this->accessToken();
		$menu = $this->httpRequest($url);
		return $menu;
	}
	
	//自定义菜单删除接口
	public function custowMenuDelete(){
		$url = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token='.$this->accessToken();
		$menu = $this->httpRequest($url);
		return $menu;
	}
	
	//获取自定义菜单配置接口
	public function getCustomMenu(){
		$url = 'https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info?access_token='.$this->accessToken();
		$menu = $this->httpRequest($url);
		return $menu;
	}
	
	//获取微信服务器IP地址
	public function getWeChatServerIp(){
		$url = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token='.$this->accessToken();
		$wechat = json_decode($this->httpRequest($url), true);
		return json($wechat);
	}
	
	//获取用户信息
	/*public function getUserInfo(){
		$accessToken = $this->accessToken();
		$openId = 'of2Uw0zyiHssUArqkKOJWjfk6InI';
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$accessToken&openid=$openId&lang=zh_CN";
		$result = $this->httpRequest($url);
		return json_decode($result, true);
	}*/
	
	//获取access_token 以及验证 access_token的有效性
	public function accessToken(){
		if(Session('access_token') && Session('expires_in') > time()){
			return Session('access_token');
		}else{
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->secret";
			$accessTokenObj = json_decode($this->httpRequest($url));
			if(isset($accessTokenObj->errcode)){
				die('获取access_token失败');
			}
			Session('access_token', $accessTokenObj->access_token);
			Session('expires_in', ($accessTokenObj->expires_in + time()));
			return Session('access_token');
		}
	}
	
	//curl请求
	public function httpRequest($url, $data=null) {
		//第一步：创建curl
		$ch = curl_init();
		//第二步：设置curl
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //以文档流的形式返回数据
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //禁止服务器端校检SSL证书
		//判断$data数据是否为空
		if(!empty($data)) {
			//模拟发送POST请求
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		//第三步：执行curl
		$output = curl_exec($ch);
		//第四步：关闭curl
		curl_close($ch);
		//把$output当做返回值返回
		return $output;
	}
	
	//参数验证
	public function checkSign(){
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];
		$token = 'wechat';
		$tmpArr = array($token, $timestamp, $nonce);
		//排序
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode($tmpArr);
		$tmpStr = sha1($tmpStr);
		if($tmpStr == $signature){
			return true;
		}else{
			return false;
		}
	}
}


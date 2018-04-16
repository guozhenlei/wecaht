<?php
/**
 * Created by PhpStorm.
 * User: gzl
 * Date: 2018/3/21
 * Time: 下午3:38
 */
namespace app\admin\Validate;

use think\Validate;

class SystemSetting extends Validate{
	
	protected $rule = [
		//基础
		'webName' => 'require|max:25', //网站名称号
		'webAdminName' => 'require|max:25', //后台系统名称
		'record' => 'require|max:50', //备案名称
		'webTitle' => 'require|max:25', //网站标题名称
		'webLog' => 'require', //网站log必填
		//上传
		'access_key' => 'require', //七牛云Key
		'access_secret' => 'require', //七牛云Secret
		'space_name' => 'require', //七牛云空间名
		'bind_domain' => 'require', //七牛云绑定的域名
		//邮箱
		'server_smtp' => 'require', //邮箱服务器地址
		'server_port' => 'require', //邮箱服务器端口
		'send_user' => 'require', //发送方账号
		'password' => 'require', //密码
		//短信
		'sms_key' => 'require', //短信Key
		'sms_secret' => 'require', //短信Secret
		'sms_sign' => 'require', //短信签名
		'sms_tem' => 'require', //短信模版
		'con_var' => 'require', //短信内容变量
		//支付宝
		'alipay_app_id' => 'require', //支付宝支付appid
		'alipay_notify_url' => 'require', //支付宝回调地址
		'alipay_public_key' => 'require', //支付宝公钥
		'merchant_public_key' => 'require', //商户公钥
		'merchant_private_key' => 'require', //商户私钥
		//微信支付
		'wechat_appid' => 'require', //微信app appid
		'wechat_app_id' => 'require', //公众号appid
		'wechat_miniapp_id' => 'require', //小程序appid
		'wechat_mch_id' => 'require', //商户号id
		'wechat_key' => 'require', //Key
		'wechat_notify_url' => 'require', //回调地址
	];
	
	protected $message = [
		//基础
		'webName.require' => '网站名称必填',
		'webName.max' => '网站名称长度不能超过25字符',
		'webAdminName' => '后台系统名称必填',
		'webAdminName.max' => '后台系统名称不能超过25字符',
		'record.require' => '备案名必填',
		'record.max' => '备案号长度不能超过50字符',
		'webTitle.require' => '网站标题必填',
		'webTitle.max' => '网站标题长度不能超过25字符',
		'webLog.require' => '网站Log必填',
		//上传
		'access_key.require' => '七牛云Key必填',
		'access_secret.require' => '七牛云Secret必填',
		'space_name.require' => '空间名必填',
		'bind_domain.require' => '七牛云绑定域名必填',
		//邮箱
		'server_smtp.require' => '服务器地址必填',
		'server_port.require' => '服务器端口号必填',
		'send_user.require' => '账号必填',
		'password.require' => '密码必填',
		//短信
		'sms_key.require' => '短信Key必填',
		'sms_secret.require' => '短信Secret必填',
		'sms_sign.require' => '短信签名必填',
		'sms_tem.require' => '短信模版必填',
		'con_var.require' => '短信内容变量必填',
		//支付宝
		'alipay_app_id.require' => 'appid必填', //支付宝支付appid
		'alipay_notify_url.require' => '回调地址必填', //支付宝回调地址
		'alipay_public_key.require' => '支付宝公钥', //支付宝公钥
		'merchant_public_key.require' => '商户公钥', //商户公钥
		'merchant_private_key.require' => '商户私钥', //商户私钥
		//微信支付
		'wechat_appid.require' => 'APPappid必填', //微信appid
		'wechat_app_id.require' => '公众号appid必填', //公众号appid
		'wechat_miniapp_id.require' => '小程序appid必填', //小程序appid
		'wechat_mch_id.require' => '商户号id必填', //商户号id
		'wechat_key.require' => 'Key必填', //Key
		'wechat_notify_url.require' => '回调地址必填', //回调地址
		
	];
	
	protected $scene = [
		'basic_edit' => ['webName', 'webAdminName', 'record', 'webTitle'],//, 'webLog'],
		'upload_edit' => ['access_key', 'access_secret', 'space_name', 'bind_domain'],
		'sms_edit' => ['sms_key', 'sms_secret', 'sms_sign', 'sms_tem', 'con_var'],
		'email_edit' => ['server_smtp', 'server_port', 'send_user', 'password'],
		'alipay_edit' => ['alipay_app_id', 'alipay_notify_url', 'alipay_public_key', 'merchant_public_key', 'merchant_private_key'],
		'wechat_edit' => ['wechat_appid', 'wechat_app_id', 'wechat_miniapp_id', 'wechat_mch_id', 'wechat_key', 'wechat_notify_url']
	];
}
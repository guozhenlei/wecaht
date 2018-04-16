<?php
/**
 * Created by PhpStorm.
 * User: gzl
 * Date: 2018/3/27
 * Time: 下午4:18
 */
namespace app\admin\model;

use think\Model;
class PaySetting extends Model{

	protected $table = 'wechat_pay_setting';
	
	/*
	 * 获取支付列表
	 * 0 未开启   1 开启
	 */
	public function	payList(){
		$data = self::all();
		$count = self::count();
		return json(['code' => '0', 'msg' => 'ok', 'count' => $count, 'data' => $data]);
	}
	
	/*
	 * 获取支付的键值
	 */
	public static function getPayKey($id){
		if($id){
			$key = self::where('id', $id)->value('pay_key');
			return !empty($key) ? $key : false;
		}
		return false;
	}
	
	/*
	 * 删除支付方式
	 */
	public static function payDel($id){
		if($id){
			$del = self::destroy($id);
			if($del){
				return true;
			}
			return false;
		}
		return false;
	}
	
	/*
	 * 更改支付状态
	 * 0  为开启  1  开启
	 */
	public static function isOpen($id){
		$pay = self::where('id', $id)->find();
		if(!empty($pay)){
			if($pay->is_open == '0'){
				$pay->is_open = '1';
			}else{
				$pay->is_open = '0';
			}
			return $pay->save();
		}
		return false;
	}
}
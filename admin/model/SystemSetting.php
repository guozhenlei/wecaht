<?php
/**
 * Created by PhpStorm.
 * User: gzl
 * Date: 2018/3/15
 * Time: 下午4:34
 */

namespace app\admin\model;

use think\Model;

class SystemSetting extends Model{
	protected $table = 'wechat_web_setting';
	//protected $name = 'web_setting';
	
	//自动写入时间
	//protected $autoWriteTimestamp = true;
	
	//查询设置
	public static function getSetting($val){
		if($val){
			$setting = self ::where('key', $val) -> find();
			if(empty($setting)){
				return false;
			}else if(empty($setting -> value)){
				return '';
			}else{
				return $setting -> value;
			}
		}
		return false;
	}
	
	/*
	 * 更新字段
	 */
	public static function updateSetting($key, $val, $name){
		if($key && $val && $name){
			$setting = self ::where('w_name', $name) -> find();
			if(empty($setting)){
				$setting = new self();
			}else{
				$setting = self::find($setting -> id);
			}
			$setting -> w_name = $name;
			$setting -> key = $key;
			$setting -> value = $val;
			$res = $setting -> save();
			return $res ? true : false;
		}
		return false;
	}
	
}
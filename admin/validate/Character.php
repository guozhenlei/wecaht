<?php
/**
 * Created by PhpStorm.
 * User: gzl
 * Date: 2018/4/12
 * Time: 下午4:34
 */

namespace app\admin\validate;

use think\Validate;

class Character extends Validate{
	protected $rule = [
		'name' => 'require|max:25',
	];
	
	protected $message = [
		'name.require' => '名称必填',
		'name.max' => '名称长度不能超过25字符'
	];
	
	protected $scene = [
		'charEdit' => ['name'],
	];
}
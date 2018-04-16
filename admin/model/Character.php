<?php
/**
 * Created by PhpStorm.
 * User: gzl
 * Date: 2018/4/12
 * Time: 上午10:46
 */
namespace app\admin\model;

use think\Model;
use app\admin\model\Method as MethodModel;

class Character extends Model{
	protected $pk = 'id';
	protected $table = 'wechat_character';
	
	/**
	 * 一对多关系
	 * @return \think\model\relation\HasMany
	 */ //角色表 对应 权限表
	public function auth(){
		return $this->hasMany('Auth', 'char_id', 'id');
	}
	
	/**
	 * 角色列表
	 * @param $page
	 * @param $limit
	 * @return \think\response\Json
	 * @throws \think\db\exception\DataNotFoundException
	 * @throws \think\db\exception\ModelNotFoundException
	 * @throws \think\exception\DbException
	 */
	public static function charList($page, $limit){
		$data = self::page($page, $limit)->select();
		$count = self::count();
		return json(['code' => '0', 'msg' => 'ok', 'count' => $count, 'data' => $data]);
	}
	
	//删除角色
	public static function charDel($id){
		return self::destroy($id);
	}
	
	/**
	 * 查找角色
	 * @param $id
	 * @return null|static
	 * @throws \think\exception\DbException
	 */
	public static function getChar($id){
		return self::get($id);
	}
	
	/**
	 * 编辑角色
	 * @param $name
	 * @param $id
	 * @return bool
	 * @throws \think\exception\DbException
	 */
	public static function charEdit($name, $id){
		if($id){
			$char = new Character;
			$update = $char->save([
				'name' => $name
			], ['id' => $id]);
			return $update ? true : false;
		}else{
			$insert = new Character([
				'name' => $name
			]);
			$insert = $insert->save();
			return $insert ? true : false;
		}
	}
}
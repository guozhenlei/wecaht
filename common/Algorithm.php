<?php
/**
 * Created by PhpStorm.
 * User: gzl
 * Date: 2018/4/8
 * Time: 下午1:45
 */
namespace app\common;

class Algorithm{
	
	/**
	 * 冒泡排序算法
	 * 冒泡排序是将数组中的前后两个相领的数值进行比较 条件成立 则进行交换 效率比较低 因为其交换的次数也比较多
	 * @param array $arr
	 * @param bool $sort 默认从大到小
	 * @return array|bool
	 */ //速度最慢
	public static function bubbleSort($arr, $sort = true){
		//核心思想 ： 依次对比，最小的数放在最后
		if(is_array($arr)){
			$len = count($arr);
			if($sort){
				for($i = 0; $i < $len; $i++){
					for($j =0; $j < $len-$i-1; $j++){
						if($arr[$j] < $arr[$j+1]){
							$tmp = $arr[$j];
							$arr[$j] = $arr[$j+1];
							$arr[$j+1] = $tmp;
						}
					}
				}
			}else{
				for($i = 0; $i < $len; $i++){
					for($j =0; $j < $len-$i-1; $j++){
						if($arr[$j] > $arr[$j+1]){
							$tmp = $arr[$j];
							$arr[$j] = $arr[$j+1];
							$arr[$j+1] = $tmp;
						}
					}
				}
			}
			return $arr;
		}
		return false;
	}
	
	/**
	 * 选择排序算法
	 * 选择排序主要是将假设数组中的第一个是最小的，循环与数组中的第一个进行比较 如果比其还小 则记录下标 进行数值交换 效率相对冒泡来说比较高
	 * @param array $arr
	 * @param bool $sort 默认从大到小
	 * @return array|bool
	 */ //速度较快
	public static function selectSort($arr, $sort = true){
		//核心思想 ： 固定一个数  然后依次和剩余的对比
		if(is_array($arr)){
			$len = count($arr);
			if($sort){
				for($i = 0; $i < $len-1; $i++){
					//第一个数固定，依次对比
					$m = $i; //假设最大值为$m, 这样记住下标的好处就是减少元素置换的次数
					for($j = $i+1; $j < $len; $j++){
						if($arr[$j] > $arr[$m]){
							$m = $j;
						}
					}
					if($m != $i){
						$tmp = $arr[$i];
						$arr[$i] = $arr[$m];
						$arr[$m] = $tmp;
					}
				}
			}else{
				for($i = 0; $i < $len-1; $i++){
					//第一个数固定，依次对比
					$m = $i; //假设最大值为$m, 这样记住下标的好处就是减少元素置换的次数
					for($j = $i+1; $j < $len; $j++){
						if($arr[$j] < $arr[$m]){
							$m = $j;
						}
					}
					if($m != $i){
						$tmp = $arr[$i];
						$arr[$i] = $arr[$m];
						$arr[$m] = $tmp;
					}
				}
			}
			return $arr;
		}
		return false;
	}
	
	/**
	 * 插入排序算法
	 * 插入排序是将插入的数据保存在变量中，与数组中的每个数比较 找到合适的位置 进行插入 效率相对来说比较高
	 * @param array $arr
	 * @param bool $sort
	 * @return mixed
	 */ //速度较快
	public static function insertSort($arr, $sort = true){
		$len = count($arr);
		if($sort){
			for($i = 0; $i <= $len; $i++){
				//取出比较的元素
				$tmp = $arr[$i];
				for($j = $i - 1; $j >= 0; $j--){
					if($arr[$j] < $tmp){
						$arr[$j+1] = $arr[$j];
						$arr[$j] = $tmp;
					}
				}
			}
			return $arr;
		}else{
			for($i = 0; $i <= $len; $i++){
				//取出比较的元素
				$tmp = $arr[$i];
				for($j = $i - 1; $j >= 0; $j--){
					if($arr[$j] > $tmp){
						$arr[$j+1] = $arr[$j];
						$arr[$j] = $tmp;
					}
				}
			}
			return $arr;
		}
	}
	
	/**
	 * 快速排序算法
	 * 通过一趟排序将要排序的数据分割成独立的两部分，其中一部分的所有数据都比另外一部分的所有数据都要小，然后再按此方法对这两部分数据分别进行快速排序，整个排序过程可以递归进行，以此达到整个数据变成有序序列。效率很高
	 * @param array $arr
	 * @param bool $sort
	 * @return array|bool
	 */ //速度最快
	public static function quickSort($arr, $sort = true){
		if($sort){
			//核心思想 ： 取数组的第一个元素，以这个元素为基准，把数组分成两个一大一小的数据，依次递归调用；
			if(!is_array($arr)) return false;
			$len = count($arr);
			if($len <= 1) return $arr;
			//定义两个空数组
			$left = $right = [];
			for($i = 1; $i < $len; $i++){
				if($arr[$i] > $arr[0]){
					$left[] = $arr[$i];
				}else{
					$right[] = $arr[$i];
				}
			}
			//递归调用
			$left = self::quickSort($left);
			$right = self::quickSort($right);
			//合并
			return array_merge($left,array($arr[0]),$right);
		}else{
			//核心思想 ： 取数组的第一个元素，以这个元素为基准，把数组分成两个一大一小的数据，依次递归调用；
			if(!is_array($arr)) return false;
			$len = count($arr);
			if($len <= 1) return $arr;
			
			//定义两个空数组
			$left = $right = [];
			
			for($i = 1; $i < $len; $i++){
				if($arr[$i] < $arr[0]){
					$left[] = $arr[$i];
				}else{
					$right[] = $arr[$i];
				}
			}
			//递归调用
			$left = self::quickSort($left);
			$right = self::quickSort($right);
			//合并
			return array_merge($left,array($arr[0]),$right);
		}
		
	}
	
}
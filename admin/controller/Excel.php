<?php
/**
 * Created by PhpStorm.
 * User: gzl
 * Date: 2018/4/3
 * Time: 下午4:55
 */
namespace app\admin\controller;

use think\Db;

class Excel extends Base{
	
	//把数据库的数据 导出到excel中 格式为csv
	public function generateCsvFile($file_name, $table, $sql, $limit=100000){
		// 输出Excel文b件头，可把user.csv换成你要的文件名
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$file_name.'.'.'csv"');
		header('Cache-Control: max-age=0');
		
		//1. 打开PHP文件句柄，php://output 表示直接输出到浏览器
		$fp = fopen('php://output', 'x+');
		
		//2. 输出Excel列名信息
		$head = array_keys(Db::table($table)->find());
		foreach ($head as $i => $v) {
			// CSV的Excel支持GBK编码，一定要转换，否则乱码
			$head[$i] = iconv('utf-8', 'gbk', $v);
		}
		
		//3. 将数据通过fputcsv写到文件句柄
		fputcsv($fp, $head);
		
		//计数器
		$num = 0;
		
		//每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
		//$limit = 100000;
		//$sql = "select * from wechat_web_setting";
		$result = mysqli_query($this->dbQuery(), $sql);
		
		// 逐行取出数据，不浪费内存
		while($row = mysqli_fetch_row($result)){
			$num ++;
			
			//刷新一下输出buffer，防止由于数据过多造成问题
			if($num >= $limit){
				ob_flush();
				flush();
				$num = 0;
			}
			
			foreach ($row as $i => $v) {
				$row[$i] = iconv('utf-8', 'gbk', $v);
			}
			fputcsv($fp, $row);
		}
	}
	
	/*public function getExcel(){
		// 输出Excel文件头，可把user.csv换成你要的文件名
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="user.csv"');
		header('Cache-Control: max-age=0');
		// 从数据库中获取数据，为了节省内存，不要把数据一次性读到内存，从句柄中一行一行读即可
		$sql = 'select * from wechat_web_setting';
		$stmt = Db::query($sql);
		// 打开PHP文件句柄，php://output 表示直接输出到浏览器
		$fp = fopen('php://output', 'x+');
		// 输出Excel列名信息
		$head = array('id', '名称', 'key', 'value');
		foreach ($head as $i => $v) {
			// CSV的Excel支持GBK编码，一定要转换，否则乱码
			$head[$i] = iconv('utf-8', 'gbk', $v);
		}
		// 将数据通过fputcsv写到文件句柄
		fputcsv($fp, $head);
		// 计数器
		$cnt = 0;
		// 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
		$limit = 100000;
		// 逐行取出数据，不浪费内存
		while ($row = $stmt->fetch(Zend_Db::FETCH_NUM)) {
			$cnt ++;
			if ($limit == $cnt) { //刷新一下输出buffer，防止由于数据过多造成问题
				ob_flush();
				flush();
				$cnt = 0;
			}
			foreach ($row as $i => $v) {
				$row[$i] = iconv('utf-8', 'gbk', $v);
			}
			fputcsv($fp, $row);
		}

	
	}*/
	
	//连接数据库
	public function dbQuery(){
		$db = mysqli_connect(config('database.hostname'), config('database.username'), config('database.password'), config('database.database'), config('database.hostport')) or die('连接数据库失败');
		mysqli_query($db,"set names utf8");
		return $db;
	}
	
	public function getTable(){
		$sql = "select * from wechat_web_setting";
		$this->getExcel('table', 'wechat_web_setting', $sql);
		echo '成功！';
	}
}
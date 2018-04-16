<?php
/**
 * Created by PhpStorm.
 * User: gzl
 * Date: 2018/4/4
 * Time: 下午1:43
 */
namespace app\common;

use think\Exception;
use think\Db;
use think\facade\Env;
require Env::get('root_path').'vendor/phpoffice/phpexcel/Classes/PHPExcel.php'; //核心类
require Env::get('root_path').'vendor/phpoffice/phpexcel/Classes/PHPExcel/Settings.php'; //设置类
require Env::get('root_path').'vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php'; //生成文件类
require Env::get('root_path').'vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel5.php'; //输出XLS文件
require Env::get('root_path').'vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007.php'; //输出XLSX文件
require Env::get('root_path').'vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/PDF.php'; //输出pdf文件
/*require Env::get('root_path').'vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/PDF/DomPDF.php'; //引入PDF库文件
require Env::get('root_path').'vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/PDF/mPDF.php'; //引入PDF库文件
require Env::get('root_path').'vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/PDF/tcPDF.php'; //引入PDF库文件*/

/**
 * 数据库数据生成文件类
 * 该类使用了php原生方法生成CSV文件，
 * 使用PHPExcel扩展生成XLS，XLSX，PDF文件，
 * 支持选择下载到服务器 and 浏览器
 * Class Excel
 * @package app\common
 */
class Excel{
	protected $host; //服务器地址
	protected $name; //数据库账号
	protected $pass; //数据库密码
	protected $database; //数据库名称
	protected $port; //端口号
	
	/**
	 * 构造方法
	 * Excel constructor.
	 */
	public function __construct(){
		//parent::__construct(); //声明父类构造方法
		$this->host = config('database.hostname');
		$this->name = config('database.username');
		$this->pass = config('database.password');
		$this->database = config('database.database');
		$this->port = config('database.hostport');
	}
	
	/**
	 * 连接数据库
	 */
	protected function dbQuery(){
		$db = mysqli_connect($this->host, $this->name, $this->pass, $this->database, $this->port) or die('连接数据库失败');
		mysqli_query($db,"set names utf8");
		return $db;
	}
	
	/**
	 * 一维数组 字符集 gbk 转化为 utf-8
	 * array $arr 一维数组
	 * CSV的Excel支持GBK编码，一定要转换，否则乱码
	 * 转化要生成文件的数据格式
	 */
	protected function conversionFormat($arr){
		if(is_array($arr)){
			foreach($arr as $k => $v){
				$arr[$k] = iconv('utf-8', 'gbk', $v);
			}
			return true;
		}
		//格式不是数组
		throw new Exception('format not array');
	}
	
	/**
	 * 生成csv格式的文件
	 * @param string $file_name 文件名称
	 * @param string $table mysql表名
	 * @param string $sql sql语句
	 * @param int $limit 条数 默认100000
	 * @throws Exception
	 */
	public function generateCsvFile($file_name, $table, $sql, $limit=100000){
		/**
		 * 输出Excel文件头
		 */
		//输出的文件类型为excel
		header('Content-Type: application/vnd.ms-excel');
		//提示下载
		header('Content-Disposition: attachment;filename="'.$file_name.'.'.'csv"');
		//header('Cache-Control: max-age=0');
		
		//1. 打开PHP文件句柄，php://output 表示直接输出到浏览器
		$fp = fopen('php://output', 'x+');
		
		//2. 输出Excel列名信息
		$head = array_keys(Db::table($table)->find());
		$this->conversionFormat($head);
		
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
			$this->conversionFormat($head);
			fputcsv($fp, $row);
		}
	}
	
	//测试生成xls文件  失败
	public function exportExcel($sql,$table,$filename){
		// 输出excel文件头
		header("Content-Type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename=".iconv("utf-8",  "GB2312",  $filename).".xls");
		header("Content-type: text/html; charset=utf-8");
		
		echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
				<html xmlns='http://www.w3.org/1999/xhtml'>
				<head><meta http-equiv='Content-Type' content='text/html;  charset=UTF-8' /></head>
				<title>".$filename."</title>
				<body>
				<table width='90%'><tr>";
		
		$tableHeader = array_keys( Db::table($table)->find() );
		
		foreach ($tableHeader as $tab){
			echo '<td >'.$tab.'</td>';
		}
		echo '</tr>';
		$res = mysqli_query($this->dbQuery(), $sql);
		while($rowCon = mysqli_fetch_row($res)){
			echo '<tr>';
			foreach ($rowCon as $v){
				echo '<td>'.$v.'</td>';
			}
			echo '</tr>';
		}
		echo '</table>';
	}
	
	/**
	 * 生成格式文件
	 * @param string $file_name
	 * @param array  $content
	 * @param string $format
	 * @param bool   $way
	 * @throws Exception
	 * @throws \PHPExcel_Exception
	 * @throws \PHPExcel_Reader_Exception
	 * @throws \PHPExcel_Writer_Exception
	 * @param  bool
	 */
	public function generateFormatFile($file_name, $content, $format='xls', $way = false){
		//创建新的PHPExcel对象
		$objPHPExcel = new \PHPExcel();
		
		//设置文档属性
		$this->setDocumentProper($objPHPExcel, $format);
		
		//设置默认字体
		$objPHPExcel
			-> getDefaultStyle()
			-> getFont()
			-> setName(' Arial')
			-> setSize(10);
		
		//标题
		$head = array_keys($content[0]); //取出数组的键名
		$this->conversionFormat($head); //转化格式 UTF-8
		
		//插入表的内容
		//$content = Db::table('wechat_web_setting')->select();
		
		//添加数据
		$arr = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S' ,'T', 'U', 'V', 'W', 'S', 'Y', 'Z'];
		$len = count($head);
		
		//插入标题
		for($i = 0; $i < $len; $i++){
			$objPHPExcel -> setActiveSheetIndex(0) -> setCellValue( $arr[$i].'1', $head[$i]);
		}
		
		//插入内容
		$lenT = count($content); //总条数
		for($i = 0; $i < $lenT; $i++){
			$lenH = count($content[$i]); //行的总列数
			for($j = 0; $j < $lenH; $j++){
				$ai = $i+2;
				$this->conversionFormat($content[$i]); //转化格式
				$objPHPExcel -> setActiveSheetIndex(0) -> setCellValue( $arr[$j].$ai, array_values($content[$i])[$j]);
			}
		}
		
		switch($format){
			case 'xls':
				$this->way($objPHPExcel, 'Excel5', $file_name, $format, $way);
				break;
			case 'xlsx':
				$this->way($objPHPExcel, 'Excel2007', $file_name, $format, $way);
				break;
			case 'pdf':
				$this->way($objPHPExcel, 'PDF', $file_name, $format, $way);
				break;
		}
	}
	
	/**
	 * 设置现在文件的名称 格式 下载位置
	 * @param  Object $objPHPExcel 对象
	 * @param string $name 对应的类名称
	 * @param string $file_name 下载的文件名名称
	 * @param string $format 文件的格式
	 * @param boolean $way 默认浏览器端下载
	 * @throws \PHPExcel_Reader_Exception
	 * @throws \PHPExcel_Writer_Exception
	 * @return boolean
	 */
	public function way($objPHPExcel, $name, $file_name, $format, $way = false){
		//是否直接浏览器下载
		if($way){
			$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $name);
			$objWriter->save($file_name.'.'.$format);
			return true;
		}
		
		//判断下载文件的格式
		if($format == 'xls'){
			header('Content-Type: application/vnd.ms-excel');
		}else if($format == 'xlsx'){
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		}else if($format == 'pdf'){
			/*//更改这些值以选择您希望使用的渲染库
			//及其服务器上的目录位置
			//$rendererName = PHPExcel_Settings::PDF_RENDERER_TCPDF;
			$rendererName  =  \PHPExcel_Settings::PDF_RENDERER_MPDF;
			//$rendererName = \PHPExcel_Settings::PDF_RENDERER_DOMPDF;
			//$rendererLibrary = 'tcPDF5.9';
			$rendererLibrary  =  'mPDF6.0';
			//$rendererLibrary = 'domPDF0.6.0beta3';
			$rendererLibraryPath  =  Env::get('root_path').'vendor/phpoffice/Classes/PHPExcel/'.' /../../../libraries/PDF/ '.$rendererLibrary;
			//$ rendererLibraryPath  =  ' / php / libraries / PDF / ' 。 $ rendererLibrary ;
			if (!\PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)){
				die(
					'NOTICE: Please set the $rendererName and $rendererLibraryPath values' .
					'<br />' .
					'at the top of this script as appropriate for your directory structure'
				);
			}*/
			header('Content-Type: application/pdf');
			
		}else{
			return false;
		}
		header('Content-Disposition: attachment;filename="'.$file_name.'.'.$format.'"');
		header('Cache-Control: max-age=0');
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $name);
		echo '456';
		$objWriter->save('php://output');
		return true;
	}
	
	//设置文档属性
	public function setDocumentProper($objPHPExcel, $format){
		if($format == 'xls'){
			$objPHPExcel
				-> getProperties()-> setCreator('GZL') //设置文件的创建者
				-> setLastModifiedBy('GZL') //设置最后修改者
				-> setTitle('XLS文件') //设置标题
				-> setSubject('XLS文件') //设置主题
				-> setDescription('good') //设置备注
				-> setKeywords('form to gzl') //设置标记
				-> setCategory('');  //设置类别
		}else if($format == 'xlsx'){
			$objPHPExcel
				-> getProperties()-> setCreator('GZL') //设置文件的创建者
				-> setLastModifiedBy('GZL') //设置最后修改者
				-> setTitle('XLSX文件') //设置标题
				-> setSubject('XLSX文件') //设置主题
				-> setDescription('good') //设置备注
				-> setKeywords('form to gzl') //设置标记
				-> setCategory('');  //设置类别
		}else if($format == 'pdf'){
			$objPHPExcel
				-> getProperties()-> setCreator('GZL') //设置文件的创建者
				-> setLastModifiedBy('GZL') //设置最后修改者
				-> setTitle('PDF文件') //设置标题
				-> setSubject('PDF文件') //设置主题
				-> setDescription('good') //设置备注
				-> setKeywords('form to gzl') //设置标记
				-> setCategory('');  //设置类别
		}else{
			return false;
		}
		return true;
	}
	
}
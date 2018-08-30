<?php

/**
 * @description: 实现数据导出到Excel的功能
 * @date 2010-10-20 下午07:18:35
 */
include_once WEB_TOR . "module/phpExcel/classes/PHPExcel.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Cell.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel2007.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/ReferenceHelper.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/CachedObjectStorageFactory.php";

class model_hr_leave_leaveExportUtil {

	//模块内容居中样式
	function toCecter($i, $objPHPExcel) {
		for($temp = 0; $temp < 21; $temp ++) {
			$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $temp, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		}
	}

	/**
	 * $cellValue为传入的二维数组
	 */
	public static function export2ExcelUtil($rowdatas) {
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		//		//创建一个Excel工作流
		//		$objPhpExcelFile = new PHPExcel();


		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //创建一个Excel阅读器
		$objPhpExcelFile = $objReader->load ( "upfile/离职信息输出报表.xls" ); //读取自定义模板
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPhpExcelFile );

		//设置保存的文件名称及路径
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;

		//以下是对工作表基本参数的设置
		//设置当前工作表的名称
		$objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '信息列表' ) );
		//设置表头及样式 设置
		$i = 3;//初始化行数
//		echo "<pre>";
//		print_r($rowdatas[0]).die();
		if (! count ( array_filter ( $rowdatas ) ) == 0) {//如果穿过来的数据不为空
			$row = $i;//设置表格初始化行数
			for($n = 0; $n < count ( $rowdatas ); $n ++) {//取得数据的总个数
				$m = 0;//初始化每行的每一列
				foreach ( $rowdatas [$n] as $field => $value ) {//将数据里的每一行的列循环遍历出来
//					echo "<pre>";
//					print_r($row+$n);
					if($field=="state"){
						if ($value=="1"){
							$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row+$n, iconv ( "gb2312", "utf-8", "未确认类型" ) );
						}else if($value=="3"){
							$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row+$n, iconv ( "gb2312", "utf-8", "已更新档案" ) );
						}else if($value=="4"){
							$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row+$n, iconv ( "gb2312", "utf-8", "已关闭" ) );
						}else{
							$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row+$n, iconv ( "gb2312", "utf-8", $value ) );
						}
							$m ++;
							continue;
					}
					if ($field=="userSelfCstatus"){
						if ($value=="WQR"){
							$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row+$n, iconv ( "gb2312", "utf-8", "未确认" ) );
						}else {
							$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row+$n, iconv ( "gb2312", "utf-8", "已确认" ) );
						}
							$m++;
							continue;
					}
					if ($field=="handoverCstatus"){
						if ($value=="0"){
							$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row+$n, iconv ( "gb2312", "utf-8", "未发起" ) );
						}else if ($value=="WQR") {
							$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row+$n, iconv ( "gb2312", "utf-8", "未确认" ) );
						}else{
							$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row+$n, iconv ( "gb2312", "utf-8", "已确认" ) );
						}
							$m++;
							continue;
					}
					if ($field=="softSate"){
						if ($value=="1"){
							$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row+$n, iconv ( "gb2312", "utf-8", "已关闭" ) );
						}else {
							$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row+$n, iconv ( "gb2312", "utf-8", "未关闭" ) );
						}
							$m++;
							continue;
					}
                    $objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row + $n,mb_convert_encoding($value,"utf-8","gbk,big5") );
					$m ++;
				}
				$i ++;
			}
		} else {
			$objPhpExcelFile->getActiveSheet ()->mergeCells ( 'A' . $i . ':' . 'W' . $i );
			for($m = 0; $m < 23; $m ++) {
				$objPhpExcelFile->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
			}
		}

		//到浏览器
		ob_end_clean (); //解决输出到浏览器出现乱码的问题
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "离职信息输出报表.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}
}
?>
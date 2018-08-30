<?php
/**
 * 服务管理Excel工具类
 */
include "module/phpExcel/classes/PHPExcel.php";
include "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include "module/phpExcel/Classes/PHPExcel/Cell.php";
include "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";

class model_service_accessorder_serviceExcelUtil extends model_base {

	/**
	 * 导入EXCEL数据到系统中
	 * @param  $file
	 * @param  $filetempname
	 */
	function readExcelData($file, $filetempname) {
		//自己设置的上传文件存放路径
		$filePath = 'upfile/';
		$filename = explode ( ".", $file ); //把上传的文件名以“.”好为准做一个数组。
		$time = date ( "y-m-d-H-i-s" ); //去当前上传的时间
		$filename [0] = $time; //取文件名替换
		$name = implode ( ".", $filename ); //上传后的文件名
		$uploadfile = $filePath . $name; //上传后的文件名地址


		//move_uploaded_file() 函数将上传的文件移动到新位置。若成功，则返回 true，否则返回 false。
		$result = move_uploaded_file ( $filetempname, $uploadfile ); //假如上传到当前目录下
		if ($result) { //如果上传文件成功，就执行导入excel操作
			$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
			$objPHPExcel = $objReader->load ( $uploadfile ); //   $objPHPExcel = $objReader->load($uploadfile);
			$sheet = $objPHPExcel->getSheet ( 0 );
			$highestRow = $sheet->getHighestRow (); // 取得总行数
			$highestColumn = $sheet->getHighestColumn (); // 取得总列数
			$dataArr = array (); //读取结果


			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );

			//循环读取excel文件,读取一条,存取到数组一条
			for($j = 2; $j <= $highestRow; $j ++) {
				$str = "";
				for($k = 0; $k <= $highestColumnIndex; $k ++) {
					$str .= iconv ( 'UTF-8', 'GBK', $sheet->getCellByColumnAndRow ( $k, $j )->getValue () ); //读取单元格
					if ($k != $highestColumnIndex) {
						$str .= '__';
					}
				}

				$rowData = explode ( "__", $str );
				array_push ( $dataArr, $rowData );
			}

			unlink ( $uploadfile ); //删除上传的excel文件
			$msg = "读取成功！";
		} else {
			$msg = "读取失败！";
		}

		return $dataArr;
	}

	/**
	 * 导出未到款完毕订单
	 *
	 */
	function ExportNotIncomeExcel($dataArr) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/accessorder_notincome_export_template.xls" ); //读取模板
		$excelActiveSheet = $objPHPExcel->getActiveSheet ();

		for($n = 0; $n < count ( $dataArr ); $n ++) {
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 0, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['docCode'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 1, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['docDate'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 2, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['customerName'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 3, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['contactUserName'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 4, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['telephone'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 5, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['chargeUserName'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 6, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['saleAmount'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 7, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['incomeMoney'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 8, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['remark'] ) );
		}

		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
		//到浏览器
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "未到款完毕配件订单.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}
	/**
	 * 导出历史维修信息
	 *
	 */
	function ExportHistoryRepairExcel($dataArr) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/repairapply_history_export_template.xls" ); //读取模板
		$excelActiveSheet = $objPHPExcel->getActiveSheet ();

		for($n = 0; $n < count ( $dataArr ); $n ++) {
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 0, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productCode'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 1, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productName'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 2, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['pattern'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 3, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['serilnoName'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 4, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['fittings'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 5, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['checkInfo'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 6, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['docCode'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 7, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['docDate'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 8, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['customerName'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 9, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['c.contactUserName'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 10,$n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['telephone'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 11,$n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['remark'] ) );
			
		}

		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
		//到浏览器
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "物料维修历史记录.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}	
}
?>

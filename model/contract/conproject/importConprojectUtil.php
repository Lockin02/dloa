<?php
/**
 * @description: 仓存管理excel操作util
 * @date 2011-07-23
 */

include "module/phpExcel/classes/PHPExcel.php";
include "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include "module/phpExcel/Classes/PHPExcel/Cell.php";
include "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";

class model_contract_conproject_importConprojectUtil extends model_base {

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
			$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' );  //use excel2007 for 2007 format
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
				if(!empty($rowData)){
					array_push ( $dataArr, $rowData );
				}
			}
			unlink ( $uploadfile ); //删除上传的excel文件
			$msg = "读取成功！";
		} else {
			$msg = "读取失败！";
		}

		return $dataArr;
	}

	/**
	 * 导出未归还的物料EXCEL
	 *
	 */
	function exportNotBackExcel($dataArr) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/notback_productitem.xls" ); //读取模板
		$excelActiveSheet = $objPHPExcel->getActiveSheet ();

		for($n = 0; $n < count ( $dataArr ); $n ++) {

			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 0, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productCode'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 1, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productName'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 2, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['pattern'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 3, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['serialnoName'] ), PHPExcel_Cell_DataType::TYPE_STRING );
			$excelActiveSheet->setCellValueByColumnAndRow ( 5, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['proNum'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 6, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['customerName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 7, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['deptName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 8, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['pickName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 9, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['outStartDate'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 10, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['outEndDate'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 11, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['docCode'] ) );
		}

		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
		//到浏览器
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "未归还物料信息.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}

	/**
	 * 导出所有未归还的物料EXCEL(包括带序列号的和不带序列号的)
	 * @author huangzf
	 */
	function exportNotAllBackExcel($dataArr) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/notback_productitem.xls" ); //读取模板
		$excelActiveSheet = $objPHPExcel->getActiveSheet ();

		for($n = 0; $n < count ( $dataArr ); $n ++) {

			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 0, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productCode'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 1, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productName'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 2, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['pattern'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 3, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['lserialnoName'] ), PHPExcel_Cell_DataType::TYPE_STRING );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 4, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['bserialnoName'] ), PHPExcel_Cell_DataType::TYPE_STRING );
			$excelActiveSheet->setCellValueByColumnAndRow ( 5, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['pNum'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 6, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['customerName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 7, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['deptName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 8, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['pickName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 9, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['outStartDate'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 10, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['outEndDate'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 11, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['docCode'] ) );
		}

		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
		//到浏览器
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "未归还物料信息.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}

	/**
	 *
	 * 导出即时库存信息
	 * @param  $rowdatas
	 */
	public static function exportIntimeListExcel($dataArr) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/intimelist_export_template.xls" ); //读取模板
		$excelActiveSheet = $objPHPExcel->getActiveSheet ();
		for($n = 0; $n < count ( $dataArr ); $n ++) {
			$excelActiveSheet->setCellValueByColumnAndRow ( 0, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['stockName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 1, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['proType'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 2, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productCode'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 3, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 4, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['actNum'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 5, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['exeNum'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 6, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['lockedNum'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 7, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['initialNum'] ) );
		}

		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
		//到浏览器
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "即时库存信息.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}

	/**
	 *
	 * 导出物料信息
	 * @param  $rowdatas
	 */
	public static function exportProductExcel($dataArr) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/productinfo_export_template.xls" ); //读取模板
		$excelActiveSheet = $objPHPExcel->getActiveSheet ();
		for($n = 0; $n < count ( $dataArr ); $n ++) {
			$excelActiveSheet->setCellValueByColumnAndRow ( 0, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['proType'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 1, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productCode'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 2, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 3, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['unitName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 4, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['pattern'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 5, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['ext1Status'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 6, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['warranty'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 7, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['arrivalPeriod'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 8, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['purchPeriod'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 9, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['purchUserName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 10, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['brand'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 11, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['packageInfo'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 12, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['material'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 13, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['color'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 14, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['leastPackNum'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 15, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['leastOrderNum'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 16, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['statTypeName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 17, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['supplier'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 18, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['remark'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 19, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['ext2'] ) ,PHPExcel_Cell_DataType::TYPE_STRING );
		}
//
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
		//到浏览器
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "物料基本信息.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}
	/**
	 *
	 * 导出出库单信息
	 * @param  $rowdatas
	 */
	public static function exportOutStockExcel($dataArr) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/stockout_export_template.xls" ); //读取模板
		$excelActiveSheet = $objPHPExcel->getActiveSheet ();
		for($n = 0; $n < count ( $dataArr ); $n ++) {
			$excelActiveSheet->setCellValueByColumnAndRow ( 0, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['auditDate'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 1, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['docCode'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 2, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['isRed'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 3, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['relDocType'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 4, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['relDocCode'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 5, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['contractName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 6, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['contractCode'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 7, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['customerName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 8, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productCode'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 9, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 10, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['pattern'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 11, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['unitName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 12, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['actOutNum'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 13, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['cost'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 14, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['subCost'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 15, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['remark'] ) );

		}

		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
		//到浏览器
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "出库清单.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}

	/**
	 * 导出物料及其配件信息
	 */
	public static function exportProductInfoAndCongigExcel($dataArr) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/productinfoandconfig_export_template.xls" ); //读取模板
		$excelActiveSheet = $objPHPExcel->getActiveSheet ();
		$markId = null;
		for($n = 0; $n < count ( $dataArr ); $n ++) {
			if (empty ( $markId ) || $markId != $dataArr [$n] ['id']) {
				$markId = $dataArr [$n] ['id'];
				$excelActiveSheet->setCellValueByColumnAndRow ( 0, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['proType'] ) );
				$excelActiveSheet->setCellValueByColumnAndRow ( 1, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productCode'] ) );
				$excelActiveSheet->setCellValueByColumnAndRow ( 2, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productName'] ) );
				$excelActiveSheet->setCellValueByColumnAndRow ( 3, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['unitName'] ) );
				$excelActiveSheet->setCellValueByColumnAndRow ( 4, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['pattern'] ) );
				$excelActiveSheet->setCellValueByColumnAndRow ( 5, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['configCode'] ) );
				$excelActiveSheet->setCellValueByColumnAndRow ( 6, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['configName'] ) );
				$excelActiveSheet->setCellValueByColumnAndRow ( 7, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['configPattern'] ) );
				$excelActiveSheet->setCellValueByColumnAndRow ( 8, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['configNum'] ) );
				$excelActiveSheet->setCellValueByColumnAndRow ( 9, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['explains'] ) );
			} else {
				$excelActiveSheet->setCellValueByColumnAndRow ( 0, $n + 2, iconv ( "GBK", "utf-8", '' ) );
				$excelActiveSheet->setCellValueByColumnAndRow ( 1, $n + 2, iconv ( "GBK", "utf-8", '' ) );
				$excelActiveSheet->setCellValueByColumnAndRow ( 2, $n + 2, iconv ( "GBK", "utf-8", '' ) );
				$excelActiveSheet->setCellValueByColumnAndRow ( 3, $n + 2, iconv ( "GBK", "utf-8", '' ) );
				$excelActiveSheet->setCellValueByColumnAndRow ( 4, $n + 2, iconv ( "GBK", "utf-8", '' ) );
				$excelActiveSheet->setCellValueByColumnAndRow ( 5, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['configCode'] ) );
				$excelActiveSheet->setCellValueByColumnAndRow ( 6, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['configName'] ) );
				$excelActiveSheet->setCellValueByColumnAndRow ( 7, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['configPattern'] ) );
				$excelActiveSheet->setCellValueByColumnAndRow ( 8, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['configNum'] ) );
				$excelActiveSheet->setCellValueByColumnAndRow ( 9, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['explains'] ) );
			}
		}

		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
		//到浏览器
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "物料及配件信息.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}

	/**
	 * 导出物料及其配件信息
	 */
	public static function exportSafeStockExcel($dataArr) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/saftstock_export_template.xls" ); //读取模板
		$excelActiveSheet = $objPHPExcel->getActiveSheet ();
		$markId = null;
		for($n = 0; $n < count ( $dataArr ); $n ++) {
			$excelActiveSheet->setCellValueByColumnAndRow ( 0, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productCode'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 1, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 2, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['pattern'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 3, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['unitName'] ) );
//			$excelActiveSheet->setCellValueByColumnAndRow ( 4, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['actNum'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 4, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['saleStock'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 5, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['oldEquStock'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 6, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['minNum'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 7, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['maxNum'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 8, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['loadNum'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 9, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['useFull'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 10, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['moq'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 11, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['price'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 12, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['purchUserName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 13, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['prepareDay'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 14, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['minAmount'] ) );

		}

		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
		//到浏览器
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "物料警告信息.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}
}
?>

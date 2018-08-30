<?php
/**
 * @description: 实现物料数据导入到系统功能
 * @date 2011-07-23
 */

include "module/phpExcel/classes/PHPExcel.php";
include "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";

class model_customer_customer_importCustomerUtil extends model_base {

	/**
	 * 导入EXCEL数据到系统中
	 * @param  $file
	 * @param  $filetempname
	 */
	function readExcelData($file, $filetempname) {
		//自己设置的上传文件存放路径
		$filePath = 'upfile/';
		$str = "";

		//下面的路径按照你PHPExcel的路径来修改
		//set_include_path ( '.' . PATH_SEPARATOR . 'D:\EXCELDEMO' . PATH_SEPARATOR . get_include_path () );


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


			//循环读取excel文件,读取一条,存取到数组一条
			for($j = 2; $j <= $highestRow; $j ++) {
				for($k = 'A'; $k <= $highestColumn; $k ++) {
					$str .= iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue () ) . '\\'; //读取单元格
				}
				//explode:函数把字符串分割为数组。
				$strs = explode ( "\\", $str );
				array_push ( $dataArr, $strs );
				$str = "";
			}

			//			unlink ( $uploadfile ); //删除上传的excel文件
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
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 3, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['serialnoName'] ),PHPExcel_Cell_DataType::TYPE_STRING);
			$excelActiveSheet->setCellValueByColumnAndRow ( 4, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['customerName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 5, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['deptName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 6, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['pickName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 7, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['outStartDate'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 8, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['outEndDate'] ) );

		}

		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
		//到浏览器
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
     * 客户联系人导入
     */
   function linkmanDate($file, $filetempname) {
		//自己设置的上传文件存放路径
		$filePath = 'upfile/';
		$str = "";

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


			//循环读取excel文件,读取一条,存取到数组一条
			for($j = 2; $j <= $highestRow; $j ++) {
				for($k = 'A'; $k <= $highestColumn; $k ++) {
					$str .= iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue () ) . '\\'; //读取单元格
				}
				//explode:函数把字符串分割为数组。
				$strs = explode ( "\\", $str );
				array_push ( $dataArr, $strs );
				$str = "";
			}
			$msg = "读取成功！";
		} else {
			$msg = "读取失败！";
		}

		return $dataArr;
	}
}
?>

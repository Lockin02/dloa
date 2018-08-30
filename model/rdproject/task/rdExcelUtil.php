<?php
/**
 * @description: 实现数据导出到Excel的功能
 * @date 2010-10-20 下午07:18:35
 */

include "module/phpExcel/classes/PHPExcel.php";
include "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";

class model_rdproject_task_rdExcelUtil {

	//上传Excel并读取 excel数据
	function upReadExcelData($file, $filetempname) {
		//自己设置的上传文件存放路径

		$filePath = UPLOADPATH.'upfile/';
		if(!is_dir($filePath)){
			mkdir($filePath);
		}
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
		if ($result){ //如果上传文件成功，就执行导入excel操作
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


	//读取模板并导出
	public static function exporTemplate($thArr, $rowdatas){
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/protemplate.xls" ); //读取模板
		$excelActiveSheet=$objPHPExcel->getActiveSheet ();
		//设置保存的文件名称及路径
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";

		$fileSave = $path . "/" . $fileName;

		$dataArr=array(
			"projectName"=>"表单导出任务",
			"status"=>"进行中",
			"chargeName"=>"黄智帆",
			"peopleNum"=>"10(个)",
			"finRate"=>"50%",
			"waptRate"=>"1%",
			"planHours"=>"50(小时)",
			"inputHours"=>"20(小时)",
			"nowStone"=>"预研阶段",
			"stoneDate"=>"2010-11-30"
		);
		$tkGsNum=array(3,5,7,9,10,4,3,2);
		$planTimeArr=array(
			array(
				"sequence"=>"1",
				"planName"=>"计划1",
				"planUseTime"=>"25(小时)",
				"useTime"=>"15(小时)",
				"finRate"=>"70%"

			),
			array(
				"sequence"=>"2",
				"planName"=>"计划2",
				"planUseTime"=>"35(小时)",
				"useTime"=>"30(小时)",
				"finRate"=>"90%"
			)
		);
//		echo "------".$objPHPExcel->getCellXfByHashCode('$projectName');

			for($j = 2; $j <= 6; $j ++) {//替换模板字段
				for($k = 'A'; $k <= 'G'; $k ++) {
					$ename= iconv ( 'utf-8', 'gb2312', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue ()); //读取单元格
					if(isset($dataArr[$ename])){
						$pvalue=$dataArr[$ename];
						$objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->setValue(iconv ( "gb2312", "utf-8", $pvalue));
					}

				}
			}
			$i=10;
			foreach($tkGsNum as $key=>$cvalue){

				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(11,$i,iconv ( "GBK", "utf-8", $cvalue));
				$i++;
			}

			//list I32
			for($n=0;$n<count($planTimeArr);$n++){
				$m=0;
				foreach($planTimeArr[$n] as $field =>$value){
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(8+$m,32+$n,iconv("GBK","utf-8",$value));
					$m+=2;
				}

			}


		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );

		$objWriter = PHPExcel_IOFactory::CreateWriter ( $objPHPExcel );
		$objWriter->save ( $fileSave );
	}

	/*
		 * $cellValue为传入的二维数组
		 */
	public static function export2ExcelUtil($thArr, $rowdatas) {
		//创建一个Excel工作流
		$objPhpExcelFile = new PHPExcel ();
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPhpExcelFile );

		//设置保存的文件名称及路径
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;

		//以下是对工作表基本参数的设置
		//设置当前工作表的名称
		$objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '项目任务列表') );

		$temp = count ( $rowdatas ) + 1;
		if( isset($rowdatas[0]['projectCode']) && isset($rowdatas[0]['projectName']) ){
			$projectCode = 'A2:A' . $temp;
			$projectName = 'B2:B' . $temp;
			$objPhpExcelFile->getActiveSheet()->mergeCells($projectCode);
			$objPhpExcelFile->getActiveSheet()->mergeCells($projectName);
		}else if( !isset($rowdatas[0]['projectCode']) && !isset($rowdatas[0]['projectName']) ){
			;
		}else{
			$projectCode = 'A2:A' . $temp;
			$objPhpExcelFile->getActiveSheet()->mergeCells($projectCode);
		}
		//设置表头及样式 设置
		for($m = 0; $m < count ( $thArr ); $m ++) {
			$objPhpExcelFile->getActiveSheet()->getDefaultColumnDimension()->setWidth(16);
			$objPhpExcelFile->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPhpExcelFile->getActiveSheet ()->getStyleByColumnAndRow ( $m, 1 )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
			$objPhpExcelFile->getActiveSheet()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPhpExcelFile->getActiveSheet ()->getStyleByColumnAndRow ( $m, 1 )->getFill ()->getStartColor ()->setARGB ( "0099CCFF" );
			$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $m, 1, iconv ( "gb2312", "utf-8", $thArr [$m] ) );
//			$temp ++;
		}
		//设置内容
		for($i = 0; $i < count ( $rowdatas ); $i ++) {
			$j = 0;
			foreach ( $rowdatas [$i] as $key => $value ) {
				if( $value == '' ){
					$value="-";
				}
				$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $j, $i + 2, iconv ( "GBK", "utf-8", $value ) );
				$j ++;
			}
		}

		//到浏览器
		ob_end_clean();//解决输出到浏览器出现乱码的问题
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "YX_EXCEL.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}

	/*
		 * $cellValue为传入的二维数组
		 */
	public static function dowcImportTemplate() {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/importTemplate.xls" ); //读取模板
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );

		//到浏览器
//		ob_end_clean();//解决输出到浏览器出现乱码的问题
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "研发任务导入模版.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
		return 1;
	}


	/*
		 * $cellValue为传入的二维数组
		 */
	public static function myTaskExcelUtil($thArr, $rowdatas) {
		//创建一个Excel工作流
		$objPhpExcelFile = new PHPExcel ();
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPhpExcelFile );

		//设置保存的文件名称及路径
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;

		//以下是对工作表基本参数的设置
		//设置当前工作表的名称
		$objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '项目任务列表') );

		//设置表头及样式 设置
		for($m = 0; $m < count ( $thArr ); $m ++) {
			$objPhpExcelFile->getActiveSheet()->getDefaultColumnDimension()->setWidth(16);
			$objPhpExcelFile->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPhpExcelFile->getActiveSheet ()->getStyleByColumnAndRow ( $m, 1 )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
			$objPhpExcelFile->getActiveSheet()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPhpExcelFile->getActiveSheet ()->getStyleByColumnAndRow ( $m, 1 )->getFill ()->getStartColor ()->setARGB ( "0099CCFF" );
			$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $m, 1, iconv ( "gb2312", "utf-8", $thArr [$m] ) );
//			$temp ++;
		}
		//设置内容
		for($i = 0; $i < count ( $rowdatas ); $i ++) {
			$j = 0;
			foreach ( $rowdatas [$i] as $key => $value ) {
				if( $value == '' ){
					$value="-";
				}
				$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $j, $i + 2, iconv ( "GBK", "utf-8", $value ) );
				$j ++;
			}
		}

		//到浏览器
		ob_end_clean();//解决输出到浏览器出现乱码的问题
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "YX_EXCEL.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}


}
?>
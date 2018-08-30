<?php
/**
 * @description: 车辆供应商excel操作util
 * @date 2014-01-09
 */
include_once "module/phpExcel/classes/PHPExcel.php";
include_once "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include_once "module/phpExcel/Classes/PHPExcel/Cell.php";
include_once "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include_once "module/phpExcel/Classes/PHPExcel/Reader/Excel2007.php";
include_once "module/phpExcel/Classes/PHPExcel/ReferenceHelper.php";
include_once "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include_once "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
include_once "model/contract/common/simple_html_dom.php";
include_once "module/phpExcel/Classes/PHPExcel/CachedObjectStorageFactory.php";

class model_outsourcing_outsourcessupp_importVehiclesuppUtil extends model_base {

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
		$filename[0] = $time; //取文件名替换
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

			//获取固定表单数据
			$dataArr['province'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B2" )->getValue () );
			$dataArr['city'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "D2" )->getValue () );
			$dataArr['suppName'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "F2" )->getValue () );
			$dataArr['registeredDate'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "H2" )->getValue () );

			$dataArr['suppCategoryName'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B3" )->getValue () );
			$dataArr['registeredFunds'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "D3" )->getValue () );
			$dataArr['legalRepre'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "F3" )->getValue () );
			$dataArr['carAmount'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "H3" )->getValue () );

			$dataArr['businessDistribute'] = str_replace ('，' ,',' ,iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B4" )->getValue () )); //防止地区间写的是中文的逗号
			$dataArr['isEquipDriver'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "D4" )->getValue () );
			$dataArr['isDriveTest'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "F4" )->getValue () );
			$dataArr['driverAmount'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "H4" )->getValue () );

			$dataArr['invoice'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B5" )->getValue () );
			$dataArr['taxPoint'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "D5" )->getValue () );

			$dataArr['tentativeTalk'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B6" )->getValue () );

			$dataArr['companyProfile'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B7" )->getValue () );

			$dataArr['linkmanName'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B9" )->getValue () );
			$dataArr['linkmanJob'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "D9" )->getValue () );
			$dataArr['linkmanPhone'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "F9" )->getValue () );
			$dataArr['linkmanMail'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "H9" )->getValue () );

			$dataArr['postcode'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B10" )->getValue () );
			$dataArr['address'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "D10" )->getValue () );

			$dataArr['bankName'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B12" )->getValue () );
			$dataArr['bankAccount'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "F12" )->getValue () );

			//循环读取excel文件,读取一条,存取到数组一条
			$dataArr['vehicle'] = array();
			$tmpArr['vehicle'] = array();
			for($j = 15; $j <= $highestRow; $j++) {
				$str = "";
				for($k = A; $k <= G; $k++ ,$k++) {
					$str .= iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue () ); //读取单元格
					if ($k != $highestColumn) {
						$str .= '\\';
					}
				}

				$rowData = explode ( "\\", $str );
				if(trim($rowData['0']) != ""){
					array_push ( $tmpArr['vehicle'], $rowData );
				}
			}
			$dataArr['vehicleNumb'] = count($tmpArr['vehicle']);
			//转换成Json格式
			foreach ($tmpArr['vehicle'] as $k => $v) {
				$dataArr['vehicle'][$k]['area'] = $v[0];
				$dataArr['vehicle'][$k]['carAmount'] = $v[1];
				$dataArr['vehicle'][$k]['driverAmount'] = $v[2];
				$dataArr['vehicle'][$k]['rentPrice'] = $v[3];
			}
			unlink ( $uploadfile ); //删除上传的excel文件
			$msg = "读取成功！";
		} else {
			$msg = "读取失败！";
		}

		return $dataArr;
	}

	function tableThead($column, $row, $content, $objPHPExcel) {
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setSize(16);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, iconv("gb2312", "utf-8", $content));
	}
	//模块列样式
	function tableTrow($column, $row, $content, $objPHPExcel) {
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension(chr(ord('A') + $column))->setWidth(12);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment :: VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
		//格式化数字为字符串，避免导致数字默认右对齐单元格
		//$objPHPExcel->getActiveSheet ()->getStyle('C11')->getNumberFormat()->setFormatCode("@");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->getStartColor()->setARGB("00ECF8FB");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, iconv("gb2312", "utf-8", $content));
	}
	//模块列样式
	function tableTrowLong($column, $row, $content, $objPHPExcel) {
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension(chr(ord('A') + $column))->setWidth(26);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment :: VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->getStartColor()->setARGB("00ECF8FB");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, iconv("gb2312", "utf-8", $content));
	}
	//模块内容居中样式
	function toCecter($i, $objPHPExcel) {
		for($temp = 0; $temp < 12; $temp ++) {
			$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $temp, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		}
	}

	function exportExcelUtil($thArr, $rowdatas, $modelName) {

		// $stime=microtime(true); //获取程序开始执行的时间
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
		// //创建一个Excel工作流
		// $objPhpExcelFile = new PHPExcel();
		$objReader = PHPExcel_IOFactory :: createReader('Excel2007'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/$modelName.xlsx"); //读取模板
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel2007($objPhpExcelFile);

		//以下是对工作表基本参数的设置
		//设置当前工作表的名称
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", $modelName));
		//设置表头及样式 设置
		$theadColumn = 0;
		if(is_array($thArr)){
			foreach ($thArr as $key => $val) {
				model_outsourcing_outsourcessupp_importVehiclesuppUtil::tableTrow($theadColumn, 1, $val, $objPhpExcelFile);
				$theadColumn++;
			}

		}
		$i = 2;

		if (!count(array_filter($rowdatas)) == 0) {
			$row = $i;
			for ($n = 0; $n < count($rowdatas); $n++) {
				$m = 0;
				foreach ($rowdatas[$n] as $field => $value) {
					$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $n, mb_convert_encoding($value,"utf-8","gbk,big5"));
					$m++;
				}
				$i++;
			}
		} else {
			$objPhpExcelFile->getActiveSheet()->mergeCells('A' . $i . ':' . $highestColumn . $i);
			for ($m = 0; $m < count($thArr); $m++) {
				$objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
				$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $i, iconv("gb2312", "utf-8", '暂无相关信息'));
			}
		}

		//到浏览器
		ob_end_clean();    //解决输出到浏览器出现乱码的问题
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "$modelName.xlsx" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}

	//表头有多行
	function export2ExcelUtil($thArr, $rowdatas, $modelName, $startRowNum) {

		// $stime=microtime(true); //获取程序开始执行的时间
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
		// //创建一个Excel工作流
		// $objPhpExcelFile = new PHPExcel();
		$objReader = PHPExcel_IOFactory :: createReader('Excel2007'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/$modelName.xlsx"); //读取模板
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel2007($objPhpExcelFile);

		//以下是对工作表基本参数的设置
		//设置当前工作表的名称
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", $modelName));
		//设置表头及样式 设置
		$theadColumn = 0;
		if(is_array($thArr)){
			foreach ($thArr as $key => $val) {
				model_outsourcing_outsourcessupp_importVehiclesuppUtil::tableTrow($theadColumn, 1, $val, $objPhpExcelFile);
				$theadColumn++;
			}

		}
		$i = $startRowNum;

		if (!count(array_filter($rowdatas)) == 0) {
			$row = $i;
			for ($n = 0; $n < count($rowdatas); $n++) {
				$m = 0;
				foreach ($rowdatas[$n] as $field => $value) {
					$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $n, mb_convert_encoding($value,"utf-8","gbk,big5"));
					$m++;
				}
				$i++;
			}
		} else {
			$objPhpExcelFile->getActiveSheet()->mergeCells('A' . $i . ':' . $highestColumn . $i);
			for ($m = 0; $m < count($thArr); $m++) {
				$objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
				$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $i, iconv("gb2312", "utf-8", '暂无相关信息'));
			}
		}

		//到浏览器
		ob_end_clean();    //解决输出到浏览器出现乱码的问题
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "$modelName.xlsx" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}

	/**
	 * 导入EXCEL数据到系统中
	 * @param  $file
	 * @param  $filetempname
	 */
	function readExcelData2($file ,$filetempname ,$startRowNum = 2) {

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
			for($j = $startRowNum ;$j <= $highestRow ;$j ++) {
				$str = "";
				for($k =  0 ;$k < $highestColumnIndex ;$k ++) {
					$str .= iconv ( 'UTF-8', 'GBK', $sheet->getCellByColumnAndRow($k ,$j)->getValue()); //读取单元格
					if ($k != $highestColumnIndex) {
						$str .= '\\';
					}
				}

				$rowData = explode ( "\\", $str );
				array_push ( $dataArr, $rowData );
			}

			unlink ( $uploadfile ); //删除上传的excel文件
			$msg = "读取成功！";
		} else {
			$msg = "读取失败！";
		}

		return $dataArr;
	}
}
?>

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

class model_hr_personnel_personnelExcelUtil {

	//模块名样式
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
		//		$objPHPExcel->getActiveSheet ()->getStyle('C11')->getNumberFormat()->setFormatCode("@");
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
		for ($temp = 0; $temp < 12; $temp++) {
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($temp, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
		}
	}

	//模块列样式
	function tableColumn($column, $row, $content, $objPHPExcel) {
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment :: VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
		//格式化数字为字符串，避免导致数字默认右对齐单元格
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->getStartColor()->setARGB("00ECF8FB");
		$objPHPExcel->getActiveSheet()->getStyle('F')->getNumberFormat()->setFormatCode("@");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, iconv("gb2312", "utf-8", $content));
	}

	 /**
	 * 档案选择导出
	 *
	 */
	 	public static function excelOutSelect($thArr, $rowdatas) {
		//		$stime=microtime(true); //获取程序开始执行的时间
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;

		$objReader = PHPExcel_IOFactory :: createReader('Excel5'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/personnelExcel.xls"); //读取模板
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

		//以下是对工作表基本参数的设置
		//设置当前工作表的名称
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", '人员档案信息'));
		//设置表头及样式 设置
		$theadColumn = 0;
		foreach ($thArr as $key => $val) {
//			if ($key == 'identityCard' || $key == 'quitReson'|| $key == 'quitInterview'|| $key == 'nowAddress' || $key == 'homeAddress'|| $key == 'personEmail'|| $key == 'compEmail'|| $key == 'mobile'|| $key == 'officeName') {
//				model_hr_personnel_personnelExcelUtil :: tableTrowLong($theadColumn, 1, $val, $objPhpExcelFile);
//			} else
				model_hr_personnel_personnelExcelUtil :: tableTrow($theadColumn, 1, $val, $objPhpExcelFile);
			$theadColumn++;
		}
		$i = 2;

		if (!count(array_filter($rowdatas)) == 0) {
			$row = $i;
			for ($n = 0; $n < count($rowdatas); $n++) {
				$m = 0;
				foreach ($rowdatas[$n] as $field => $value) {
						$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row + $n, mb_convert_encoding($value,"utf-8","gbk,big5") );
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
		ob_end_clean(); //解决输出到浏览器出现乱码的问题
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "人员档案信息.xls" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}

	 /**
	 * 合同选择导出
	 *
	 */
	 	public static function excelOutContract($thArr, $rowdatas) {
		//		$stime=microtime(true); //获取程序开始执行的时间
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;

		$objReader = PHPExcel_IOFactory :: createReader('Excel5'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/personnelExcel.xls"); //读取模板
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

		//以下是对工作表基本参数的设置
		//设置当前工作表的名称
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", '合同信息'));
		//设置表头及样式 设置
		$theadColumn = 0;
		foreach ($thArr as $key => $val) {
				model_hr_personnel_personnelExcelUtil :: tableTrow($theadColumn, 1, $val, $objPhpExcelFile);
				$theadColumn++;
		}
		$i = 2;

		if (!count(array_filter($rowdatas)) == 0) {
			$row = $i;
			for ($n = 0; $n < count($rowdatas); $n++) {
				$m = 0;
				foreach ($rowdatas[$n] as $field => $value) {
						$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row + $n, mb_convert_encoding($value,"utf-8","gbk,big5") );
					$m++;
				}
				$i++;
			}
		} else {
			if(count($rowdatas)>0){
				$objPhpExcelFile->getActiveSheet()->mergeCells('A' . $i . ':' . $highestColumn . $i);
			}
			for ($m = 0; $m < count($thArr); $m++) {
				$objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
				$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow(0, $i, iconv("gb2312", "utf-8", '暂无相关信息'));
			}
		}
		//到浏览器
		ob_end_clean(); //解决输出到浏览器出现乱码的问题
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "合同信息.xls" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}

		 /**
	 *项目经历选择导出
	 *
	 */
	 	public static function excelOutProject($thArr, $rowdatas) {
		//		$stime=microtime(true); //获取程序开始执行的时间
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;

		$objReader = PHPExcel_IOFactory :: createReader('Excel5'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/personnelExcel.xls"); //读取模板
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

		//以下是对工作表基本参数的设置
		//设置当前工作表的名称
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", '项目经历信息'));
		//设置表头及样式 设置
		$theadColumn = 0;
		foreach ($thArr as $key => $val) {
				model_hr_personnel_personnelExcelUtil :: tableTrow($theadColumn, 1, $val, $objPhpExcelFile);
				$theadColumn++;
		}
		$i = 2;

		if (!count(array_filter($rowdatas)) == 0) {
			$row = $i;
			for ($n = 0; $n < count($rowdatas); $n++) {
				$m = 0;
				foreach ($rowdatas[$n] as $field => $value) {
						$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row + $n, mb_convert_encoding($value,"utf-8","gbk,big5"));
					$m++;
				}
				$i++;
			}
		} else {
			if(count($rowdatas)>0){
				$objPhpExcelFile->getActiveSheet()->mergeCells('A' . $i . ':' . $highestColumn . $i);
			}
			for ($m = 0; $m < count($thArr); $m++) {
				$objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
				$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow(0, $i, iconv("gb2312", "utf-8", '暂无相关信息'));
			}
		}
		//到浏览器
		ob_end_clean(); //解决输出到浏览器出现乱码的问题
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "项目经历信息.xls" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}


		 /**
	 * 教育经历选择导出
	 *
	 */
	 	public static function excelOutEducation($thArr, $rowdatas) {
		//		$stime=microtime(true); //获取程序开始执行的时间
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;

		$objReader = PHPExcel_IOFactory :: createReader('Excel5'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/personnelExcel.xls"); //读取模板
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

		//以下是对工作表基本参数的设置
		//设置当前工作表的名称
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", '教育经历信息'));
		//设置表头及样式 设置
		$theadColumn = 0;
		foreach ($thArr as $key => $val) {
				model_hr_personnel_personnelExcelUtil :: tableTrow($theadColumn, 1, $val, $objPhpExcelFile);
				$theadColumn++;
		}
		$i = 2;

		if (!count(array_filter($rowdatas)) == 0) {
			$row = $i;
			for ($n = 0; $n < count($rowdatas); $n++) {
				$m = 0;
				foreach ($rowdatas[$n] as $field => $value) {
						$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row + $n, mb_convert_encoding($value,"utf-8","gbk,big5"));
					$m++;
				}
				$i++;
			}
		} else {
			if(count($rowdatas)>0){
				$objPhpExcelFile->getActiveSheet()->mergeCells('A' . $i . ':' . $highestColumn . $i);
			}
			for ($m = 0; $m < count($thArr); $m++) {
				$objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
				$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow(0, $i, iconv("gb2312", "utf-8", '暂无相关信息'));
			}
		}
		//到浏览器
		ob_end_clean(); //解决输出到浏览器出现乱码的问题
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "教育经历信息.xls" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}


		 /**
	 * 工作经历选择导出
	 *
	 */
	 	public static function excelOutWork($thArr, $rowdatas) {
		//		$stime=microtime(true); //获取程序开始执行的时间
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;

		$objReader = PHPExcel_IOFactory :: createReader('Excel5'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/personnelExcel.xls"); //读取模板
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

		//以下是对工作表基本参数的设置
		//设置当前工作表的名称
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", '工作经历信息'));
		//设置表头及样式 设置
		$theadColumn = 0;
		foreach ($thArr as $key => $val) {
				model_hr_personnel_personnelExcelUtil :: tableTrow($theadColumn, 1, $val, $objPhpExcelFile);
				$theadColumn++;
		}
		$i = 2;

		if (!count(array_filter($rowdatas)) == 0) {
			$row = $i;
			for ($n = 0; $n < count($rowdatas); $n++) {
				$m = 0;
				foreach ($rowdatas[$n] as $field => $value) {
						$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row + $n, mb_convert_encoding($value,"utf-8","gbk,big5") );
					$m++;
				}
				$i++;
			}
		} else {
			if(count($rowdatas)>0){
				$objPhpExcelFile->getActiveSheet()->mergeCells('A' . $i . ':' . $highestColumn . $i);
			}
			for ($m = 0; $m < count($thArr); $m++) {
				$objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
				$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow(0, $i, iconv("gb2312", "utf-8", '暂无相关信息'));
			}
		}
		//到浏览器
		ob_end_clean(); //解决输出到浏览器出现乱码的问题
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "工作经历信息.xls" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}


		 /**
	 * 社会关系选择导出
	 *
	 */
	 	public static function excelOutSociety($thArr, $rowdatas) {
		//		$stime=microtime(true); //获取程序开始执行的时间
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;

		$objReader = PHPExcel_IOFactory :: createReader('Excel5'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/personnelExcel.xls"); //读取模板
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

		//以下是对工作表基本参数的设置
		//设置当前工作表的名称
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", '社会关系信息'));
		//设置表头及样式 设置
		$theadColumn = 0;
		foreach ($thArr as $key => $val) {
				model_hr_personnel_personnelExcelUtil :: tableTrow($theadColumn, 1, $val, $objPhpExcelFile);
				$theadColumn++;
		}
		$i = 2;

		if (!count(array_filter($rowdatas)) == 0) {
			$row = $i;
			for ($n = 0; $n < count($rowdatas); $n++) {
				$m = 0;
				foreach ($rowdatas[$n] as $field => $value) {
						$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row + $n, mb_convert_encoding($value,"utf-8","gbk,big5") );
					$m++;
				}
				$i++;
			}
		} else {
			if(count($rowdatas)>0){
				$objPhpExcelFile->getActiveSheet()->mergeCells('A' . $i . ':' . $highestColumn . $i);
			}
			for ($m = 0; $m < count($thArr); $m++) {
				$objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
				$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow(0, $i, iconv("gb2312", "utf-8", '暂无相关信息'));
			}
		}
		//到浏览器
		ob_end_clean(); //解决输出到浏览器出现乱码的问题
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "社会关系信息.xls" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}




		 /**
	 * 资格证书选择导出
	 *
	 */
	 	public static function excelOutCertificate($thArr, $rowdatas) {
		//		$stime=microtime(true); //获取程序开始执行的时间
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;

		$objReader = PHPExcel_IOFactory :: createReader('Excel5'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/personnelExcel.xls"); //读取模板
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

		//以下是对工作表基本参数的设置
		//设置当前工作表的名称
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", '资格证书信息'));
		//设置表头及样式 设置
		$theadColumn = 0;
		foreach ($thArr as $key => $val) {
				model_hr_personnel_personnelExcelUtil :: tableTrow($theadColumn, 1, $val, $objPhpExcelFile);
				$theadColumn++;
		}
		$i = 2;

		if (!count(array_filter($rowdatas)) == 0) {
			$row = $i;
			for ($n = 0; $n < count($rowdatas); $n++) {
				$m = 0;
				foreach ($rowdatas[$n] as $field => $value) {
						$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row + $n, mb_convert_encoding($value,"utf-8","gbk,big5") );
					$m++;
				}
				$i++;
			}
		} else {
			if(count($rowdatas)>0){
				$objPhpExcelFile->getActiveSheet()->mergeCells('A' . $i . ':' . $highestColumn . $i);
			}
			for ($m = 0; $m < count($thArr); $m++) {
				$objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
				$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow(0, $i, iconv("gb2312", "utf-8", '暂无相关信息'));
			}
		}
		//到浏览器
		ob_end_clean(); //解决输出到浏览器出现乱码的问题
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "资格证书信息.xls" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}
	 /**
	 * 奖惩信息选择导出
	 *
	 */
	 	public static function excelOutIncentive($thArr, $rowdatas) {
		//		$stime=microtime(true); //获取程序开始执行的时间
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;

		$objReader = PHPExcel_IOFactory :: createReader('Excel5'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/personnelExcel.xls"); //读取模板
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

		//以下是对工作表基本参数的设置
		//设置当前工作表的名称
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", '奖惩信息'));
		//设置表头及样式 设置
		$theadColumn = 0;
		foreach ($thArr as $key => $val) {
				model_hr_personnel_personnelExcelUtil :: tableTrow($theadColumn, 1, $val, $objPhpExcelFile);
				$theadColumn++;
		}
		$i = 2;

		if (!count(array_filter($rowdatas)) == 0) {
			$row = $i;
			for ($n = 0; $n < count($rowdatas); $n++) {
				$m = 0;
				foreach ($rowdatas[$n] as $field => $value) {
						$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row + $n, mb_convert_encoding($value,"utf-8","gbk,big5") );
					$m++;
				}
				$i++;
			}
		} else {
			if(count($rowdatas)>0){
				$objPhpExcelFile->getActiveSheet()->mergeCells('A' . $i . ':' . $highestColumn . $i);
			}
			for ($m = 0; $m < count($thArr); $m++) {
				$objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
				$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow(0, $i, iconv("gb2312", "utf-8", '暂无相关信息'));
			}
		}
		//到浏览器
		ob_end_clean(); //解决输出到浏览器出现乱码的问题
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "奖惩信息.xls" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}


		 /**
	 *入职信息导出
	 *
	 */
	 	public static function excelOutEntryNotice($thArr, $rowdatas) {
		//		$stime=microtime(true); //获取程序开始执行的时间
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;

		$objReader = PHPExcel_IOFactory :: createReader('Excel5'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/personnelExcel.xls"); //读取模板
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

		//以下是对工作表基本参数的设置
		//设置当前工作表的名称
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", '入职信息'));
		//设置表头及样式 设置
		$theadColumn = 0;
		foreach ($thArr as $key => $val) {
				model_hr_personnel_personnelExcelUtil :: tableTrow($theadColumn, 1, $val, $objPhpExcelFile);
				$theadColumn++;
		}
		$i = 2;

		if (!count(array_filter($rowdatas)) == 0) {
			$row = $i;
			for ($n = 0; $n < count($rowdatas); $n++) {
				$m = 0;
				foreach ($rowdatas[$n] as $field => $value) {
						$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row + $n, iconv ( "gb2312", "utf-8", $value ) );
					$m++;
				}
				$i++;
			}
		} else {
			if(count($rowdatas)>0){
				$objPhpExcelFile->getActiveSheet()->mergeCells('A' . $i . ':' . $highestColumn . $i);
			}
			for ($m = 0; $m < count($thArr); $m++) {
				$objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
				$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow(0, $i, iconv("gb2312", "utf-8", '暂无相关信息'));
			}
		}
		//到浏览器
		ob_end_clean(); //解决输出到浏览器出现乱码的问题
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "入职信息.xls" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}

	/**
	 *人员信息导出
	 *
	 */
	public static function excelOutPersonInfo($thArr,$dataArr) {
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load ( "upfile/工程人员状态模板.xls" ); //读取模板
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPhpExcelFile );
		//设置保存的文件名称及路径
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 20 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;
		$objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '工程人员状态' ) );
		$theadColumn = 0;
		//设置表头及样式 设置
		$i = 2;
		if (! count ( array_filter ( $dataArr ) ) == 0) {
// 		print_r($dataArr);
			$row = $i;
			for($n = 0; $n < count ( $dataArr ); $n ++) {
				$m = 0;
				foreach ( $dataArr [$n] as $field => $value ) {
					$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row + $n,mb_convert_encoding($value,"utf-8","gbk,big5"));
					$m ++;
				}
				$i ++;
			}
		} else {
			$objPhpExcelFile->getActiveSheet ()->mergeCells ( 'A' . $i . ':' . 'O' . $i );
			for($m = 0; $m < 20; $m ++) {
				$objPhpExcelFile->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
			}
		}

		//到浏览器
		ob_end_clean (); //解决输出到浏览器出现乱码的问题
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "工程人员状态.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}


}
?>
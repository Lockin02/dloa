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

class model_supplierManage_formal_importSupplierUtil extends model_base {

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

			//获取固定表单数据
			$dataArr['suppName']=iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B3" )->getValue () );
			$dataArr['products']=iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B4" )->getValue () );
			$dataArr['address']=iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B5" )->getValue () );
			$dataArr['legalRepre']=iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B6" )->getValue () );
			$dataArr['registeredFunds']=iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "D6" )->getValue () );
			$dataArr['bankName']=iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B7" )->getValue () );
			$dataArr['accountNum']=iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "D7" )->getValue () );
			$dataArr['businRegistCode']=iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B8" )->getValue () );
			$dataArr['businessCode']=iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "D8" )->getValue () );
			$dataArr['employeesNum']=iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B9" )->getValue () );
			$dataArr['companySize']=iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "D9" )->getValue () );
			$dataArr['linkman']=array();
			//循环读取excel文件,读取一条,存取到数组一条
			for($j = 12; $j <= $highestRow; $j++) {
				$str = "";
					for($k = A; $k <= D; $k++) {
						$str .= iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue () ); //读取单元格
						if ($k != $highestColumn) {
							$str .= '\\';
						}
					}

					$rowData = explode ( "\\", $str );
					if(trim($rowData['0'])!=""){
						array_push ( $dataArr['linkman'], $rowData );
					}
			}
			$dataArr['linkmanNumb']=count($dataArr['linkman']);
			unlink ( $uploadfile ); //删除上传的excel文件
			$msg = "读取成功！";
		} else {
			$msg = "读取失败！";
		}

		return $dataArr;
	}
}
?>

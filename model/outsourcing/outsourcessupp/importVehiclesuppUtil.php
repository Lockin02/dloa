<?php
/**
 * @description: ������Ӧ��excel����util
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
	 * ����EXCEL���ݵ�ϵͳ��
	 * @param  $file
	 * @param  $filetempname
	 */
	function readExcelData($file, $filetempname) {
		//�Լ����õ��ϴ��ļ����·��
		$filePath = 'upfile/';
		$filename = explode ( ".", $file ); //���ϴ����ļ����ԡ�.����Ϊ׼��һ�����顣
		$time = date ( "y-m-d-H-i-s" ); //ȥ��ǰ�ϴ���ʱ��
		$filename[0] = $time; //ȡ�ļ����滻
		$name = implode ( ".", $filename ); //�ϴ�����ļ���
		$uploadfile = $filePath . $name; //�ϴ�����ļ�����ַ

		//move_uploaded_file() �������ϴ����ļ��ƶ�����λ�á����ɹ����򷵻� true�����򷵻� false��
		$result = move_uploaded_file ( $filetempname, $uploadfile ); //�����ϴ�����ǰĿ¼��
		if ($result) { //����ϴ��ļ��ɹ�����ִ�е���excel����
			$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
			$objPHPExcel = $objReader->load ( $uploadfile ); //   $objPHPExcel = $objReader->load($uploadfile);
			$sheet = $objPHPExcel->getSheet ( 0 );
			$highestRow = $sheet->getHighestRow (); // ȡ��������
			$highestColumn = $sheet->getHighestColumn (); // ȡ��������
			$dataArr = array (); //��ȡ���

			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );

			//��ȡ�̶�������
			$dataArr['province'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B2" )->getValue () );
			$dataArr['city'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "D2" )->getValue () );
			$dataArr['suppName'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "F2" )->getValue () );
			$dataArr['registeredDate'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "H2" )->getValue () );

			$dataArr['suppCategoryName'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B3" )->getValue () );
			$dataArr['registeredFunds'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "D3" )->getValue () );
			$dataArr['legalRepre'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "F3" )->getValue () );
			$dataArr['carAmount'] = iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "H3" )->getValue () );

			$dataArr['businessDistribute'] = str_replace ('��' ,',' ,iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B4" )->getValue () )); //��ֹ������д�������ĵĶ���
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

			//ѭ����ȡexcel�ļ�,��ȡһ��,��ȡ������һ��
			$dataArr['vehicle'] = array();
			$tmpArr['vehicle'] = array();
			for($j = 15; $j <= $highestRow; $j++) {
				$str = "";
				for($k = A; $k <= G; $k++ ,$k++) {
					$str .= iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue () ); //��ȡ��Ԫ��
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
			//ת����Json��ʽ
			foreach ($tmpArr['vehicle'] as $k => $v) {
				$dataArr['vehicle'][$k]['area'] = $v[0];
				$dataArr['vehicle'][$k]['carAmount'] = $v[1];
				$dataArr['vehicle'][$k]['driverAmount'] = $v[2];
				$dataArr['vehicle'][$k]['rentPrice'] = $v[3];
			}
			unlink ( $uploadfile ); //ɾ���ϴ���excel�ļ�
			$msg = "��ȡ�ɹ���";
		} else {
			$msg = "��ȡʧ�ܣ�";
		}

		return $dataArr;
	}

	function tableThead($column, $row, $content, $objPHPExcel) {
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setSize(16);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, iconv("gb2312", "utf-8", $content));
	}
	//ģ������ʽ
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
		//��ʽ������Ϊ�ַ��������⵼������Ĭ���Ҷ��뵥Ԫ��
		//$objPHPExcel->getActiveSheet ()->getStyle('C11')->getNumberFormat()->setFormatCode("@");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->getStartColor()->setARGB("00ECF8FB");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, iconv("gb2312", "utf-8", $content));
	}
	//ģ������ʽ
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
	//ģ�����ݾ�����ʽ
	function toCecter($i, $objPHPExcel) {
		for($temp = 0; $temp < 12; $temp ++) {
			$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $temp, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		}
	}

	function exportExcelUtil($thArr, $rowdatas, $modelName) {

		// $stime=microtime(true); //��ȡ����ʼִ�е�ʱ��
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
		// //����һ��Excel������
		// $objPhpExcelFile = new PHPExcel();
		$objReader = PHPExcel_IOFactory :: createReader('Excel2007'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/$modelName.xlsx"); //��ȡģ��
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel2007($objPhpExcelFile);

		//�����ǶԹ������������������
		//���õ�ǰ�����������
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", $modelName));
		//���ñ�ͷ����ʽ ����
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
				$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $i, iconv("gb2312", "utf-8", '���������Ϣ'));
			}
		}

		//�������
		ob_end_clean();    //��������������������������
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

	//��ͷ�ж���
	function export2ExcelUtil($thArr, $rowdatas, $modelName, $startRowNum) {

		// $stime=microtime(true); //��ȡ����ʼִ�е�ʱ��
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
		// //����һ��Excel������
		// $objPhpExcelFile = new PHPExcel();
		$objReader = PHPExcel_IOFactory :: createReader('Excel2007'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/$modelName.xlsx"); //��ȡģ��
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel2007($objPhpExcelFile);

		//�����ǶԹ������������������
		//���õ�ǰ�����������
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", $modelName));
		//���ñ�ͷ����ʽ ����
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
				$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $i, iconv("gb2312", "utf-8", '���������Ϣ'));
			}
		}

		//�������
		ob_end_clean();    //��������������������������
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
	 * ����EXCEL���ݵ�ϵͳ��
	 * @param  $file
	 * @param  $filetempname
	 */
	function readExcelData2($file ,$filetempname ,$startRowNum = 2) {

		//�Լ����õ��ϴ��ļ����·��
		$filePath = 'upfile/';
		$filename = explode ( ".", $file ); //���ϴ����ļ����ԡ�.����Ϊ׼��һ�����顣
		$time = date ( "y-m-d-H-i-s" ); //ȥ��ǰ�ϴ���ʱ��
		$filename [0] = $time; //ȡ�ļ����滻
		$name = implode ( ".", $filename ); //�ϴ�����ļ���
		$uploadfile = $filePath . $name; //�ϴ�����ļ�����ַ


		//move_uploaded_file() �������ϴ����ļ��ƶ�����λ�á����ɹ����򷵻� true�����򷵻� false��
		$result = move_uploaded_file ( $filetempname, $uploadfile ); //�����ϴ�����ǰĿ¼��
		if ($result) { //����ϴ��ļ��ɹ�����ִ�е���excel����
			$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
			$objPHPExcel = $objReader->load ( $uploadfile ); //   $objPHPExcel = $objReader->load($uploadfile);
			$sheet = $objPHPExcel->getSheet ( 0 );
			$highestRow = $sheet->getHighestRow (); // ȡ��������
			$highestColumn = $sheet->getHighestColumn (); // ȡ��������
			$dataArr = array (); //��ȡ���

			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );

			//ѭ����ȡexcel�ļ�,��ȡһ��,��ȡ������һ��
			for($j = $startRowNum ;$j <= $highestRow ;$j ++) {
				$str = "";
				for($k =  0 ;$k < $highestColumnIndex ;$k ++) {
					$str .= iconv ( 'UTF-8', 'GBK', $sheet->getCellByColumnAndRow($k ,$j)->getValue()); //��ȡ��Ԫ��
					if ($k != $highestColumnIndex) {
						$str .= '\\';
					}
				}

				$rowData = explode ( "\\", $str );
				array_push ( $dataArr, $rowData );
			}

			unlink ( $uploadfile ); //ɾ���ϴ���excel�ļ�
			$msg = "��ȡ�ɹ���";
		} else {
			$msg = "��ȡʧ�ܣ�";
		}

		return $dataArr;
	}
}
?>

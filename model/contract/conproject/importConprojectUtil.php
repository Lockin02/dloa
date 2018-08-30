<?php
/**
 * @description: �ִ����excel����util
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
	 * ����EXCEL���ݵ�ϵͳ��
	 * @param  $file
	 * @param  $filetempname
	 */
	function readExcelData($file, $filetempname) {
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
			$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' );  //use excel2007 for 2007 format
			$objPHPExcel = $objReader->load ( $uploadfile ); //   $objPHPExcel = $objReader->load($uploadfile);
				
			$sheet = $objPHPExcel->getSheet ( 0 );
			$highestRow = $sheet->getHighestRow (); // ȡ��������
			$highestColumn = $sheet->getHighestColumn (); // ȡ��������
			$dataArr = array (); //��ȡ���

			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );

			//ѭ����ȡexcel�ļ�,��ȡһ��,��ȡ������һ��
			for($j = 2; $j <= $highestRow; $j ++) {
				$str = "";
				for($k = 0; $k <= $highestColumnIndex; $k ++) {
					$str .= iconv ( 'UTF-8', 'GBK', $sheet->getCellByColumnAndRow ( $k, $j )->getValue () ); //��ȡ��Ԫ��
					if ($k != $highestColumnIndex) {
						$str .= '__';
					}
				}
				$rowData = explode ( "__", $str );
				if(!empty($rowData)){
					array_push ( $dataArr, $rowData );
				}
			}
			unlink ( $uploadfile ); //ɾ���ϴ���excel�ļ�
			$msg = "��ȡ�ɹ���";
		} else {
			$msg = "��ȡʧ�ܣ�";
		}

		return $dataArr;
	}

	/**
	 * ����δ�黹������EXCEL
	 *
	 */
	function exportNotBackExcel($dataArr) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/notback_productitem.xls" ); //��ȡģ��
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
		//�������
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "δ�黹������Ϣ.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}

	/**
	 * ��������δ�黹������EXCEL(���������кŵĺͲ������кŵ�)
	 * @author huangzf
	 */
	function exportNotAllBackExcel($dataArr) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/notback_productitem.xls" ); //��ȡģ��
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
		//�������
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "δ�黹������Ϣ.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}

	/**
	 *
	 * ������ʱ�����Ϣ
	 * @param  $rowdatas
	 */
	public static function exportIntimeListExcel($dataArr) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/intimelist_export_template.xls" ); //��ȡģ��
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
		//�������
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "��ʱ�����Ϣ.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}

	/**
	 *
	 * ����������Ϣ
	 * @param  $rowdatas
	 */
	public static function exportProductExcel($dataArr) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/productinfo_export_template.xls" ); //��ȡģ��
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
		//�������
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "���ϻ�����Ϣ.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}
	/**
	 *
	 * �������ⵥ��Ϣ
	 * @param  $rowdatas
	 */
	public static function exportOutStockExcel($dataArr) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/stockout_export_template.xls" ); //��ȡģ��
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
		//�������
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "�����嵥.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}

	/**
	 * �������ϼ��������Ϣ
	 */
	public static function exportProductInfoAndCongigExcel($dataArr) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/productinfoandconfig_export_template.xls" ); //��ȡģ��
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
		//�������
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "���ϼ������Ϣ.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}

	/**
	 * �������ϼ��������Ϣ
	 */
	public static function exportSafeStockExcel($dataArr) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/saftstock_export_template.xls" ); //��ȡģ��
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
		//�������
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "���Ͼ�����Ϣ.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}
}
?>

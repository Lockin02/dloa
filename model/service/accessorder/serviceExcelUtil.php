<?php
/**
 * �������Excel������
 */
include "module/phpExcel/classes/PHPExcel.php";
include "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include "module/phpExcel/Classes/PHPExcel/Cell.php";
include "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";

class model_service_accessorder_serviceExcelUtil extends model_base {

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
			$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
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
				array_push ( $dataArr, $rowData );
			}

			unlink ( $uploadfile ); //ɾ���ϴ���excel�ļ�
			$msg = "��ȡ�ɹ���";
		} else {
			$msg = "��ȡʧ�ܣ�";
		}

		return $dataArr;
	}

	/**
	 * ����δ������϶���
	 *
	 */
	function ExportNotIncomeExcel($dataArr) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/accessorder_notincome_export_template.xls" ); //��ȡģ��
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
		//�������
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "δ��������������.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}
	/**
	 * ������ʷά����Ϣ
	 *
	 */
	function ExportHistoryRepairExcel($dataArr) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/repairapply_history_export_template.xls" ); //��ȡģ��
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
		//�������
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "����ά����ʷ��¼.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}	
}
?>

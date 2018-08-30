<?php
/**
 * @description: ʵ���������ݵ��뵽ϵͳ����
 * @date 2011-07-23
 */

include "module/phpExcel/classes/PHPExcel.php";
include "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";

class model_customer_customer_importCustomerUtil extends model_base {

	/**
	 * ����EXCEL���ݵ�ϵͳ��
	 * @param  $file
	 * @param  $filetempname
	 */
	function readExcelData($file, $filetempname) {
		//�Լ����õ��ϴ��ļ����·��
		$filePath = 'upfile/';
		$str = "";

		//�����·��������PHPExcel��·�����޸�
		//set_include_path ( '.' . PATH_SEPARATOR . 'D:\EXCELDEMO' . PATH_SEPARATOR . get_include_path () );


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


			//ѭ����ȡexcel�ļ�,��ȡһ��,��ȡ������һ��
			for($j = 2; $j <= $highestRow; $j ++) {
				for($k = 'A'; $k <= $highestColumn; $k ++) {
					$str .= iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue () ) . '\\'; //��ȡ��Ԫ��
				}
				//explode:�������ַ����ָ�Ϊ���顣
				$strs = explode ( "\\", $str );
				array_push ( $dataArr, $strs );
				$str = "";
			}

			//			unlink ( $uploadfile ); //ɾ���ϴ���excel�ļ�
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
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 3, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['serialnoName'] ),PHPExcel_Cell_DataType::TYPE_STRING);
			$excelActiveSheet->setCellValueByColumnAndRow ( 4, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['customerName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 5, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['deptName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 6, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['pickName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 7, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['outStartDate'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 8, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['outEndDate'] ) );

		}

		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
		//�������
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
     * �ͻ���ϵ�˵���
     */
   function linkmanDate($file, $filetempname) {
		//�Լ����õ��ϴ��ļ����·��
		$filePath = 'upfile/';
		$str = "";

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


			//ѭ����ȡexcel�ļ�,��ȡһ��,��ȡ������һ��
			for($j = 2; $j <= $highestRow; $j ++) {
				for($k = 'A'; $k <= $highestColumn; $k ++) {
					$str .= iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue () ) . '\\'; //��ȡ��Ԫ��
				}
				//explode:�������ַ����ָ�Ϊ���顣
				$strs = explode ( "\\", $str );
				array_push ( $dataArr, $strs );
				$str = "";
			}
			$msg = "��ȡ�ɹ���";
		} else {
			$msg = "��ȡʧ�ܣ�";
		}

		return $dataArr;
	}
}
?>

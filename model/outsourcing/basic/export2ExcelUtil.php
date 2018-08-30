<?php
include_once "module/phpExcel/Classes/PHPExcel.php";
include_once "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include "module/phpExcel/Classes/PHPExcel/Cell.php";
include_once "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include_once "module/phpExcel/Classes/PHPExcel/Reader/Excel2007.php";
include_once "module/phpExcel/Classes/PHPExcel/ReferenceHelper.php";
include_once "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include_once "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
include ('model/contract/common/simple_html_dom.php');
include_once "module/phpExcel/Classes/PHPExcel/CachedObjectStorageFactory.php";
class model_outsourcing_basic_export2ExcelUtil extends model_base {
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
		//		$objPHPExcel->getActiveSheet ()->getStyle('C11')->getNumberFormat()->setFormatCode("@");
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

    function export2ExcelUtil($thArr, $rowdatas, $modelName) {

	// $stime=microtime(true); //��ȡ����ʼִ�е�ʱ��
	PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
	// //����һ��Excel������
	// $objPhpExcelFile = new PHPExcel();
	$objReader = PHPExcel_IOFactory :: createReader('Excel2007'); //use excel2007 for 2007 format
	$objPhpExcelFile = $objReader->load ("upfile/$modelName.xlsx"); //��ȡģ��
	//Excel2003����ǰ�ĸ�ʽ
	$objWriter = new PHPExcel_Writer_Excel2007($objPhpExcelFile);

	//�����ǶԹ������������������
	//���õ�ǰ�����������
	$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", $modelName));
	//���ñ�ͷ����ʽ ����
	// $highestColumn = chr(ord('A') + count($thArr) - 1);
	// $objPhpExcelFile->getActiveSheet()->mergeCells('A1:G1');
	// for ($m = 0; $m < count($thArr); $m++) {
	// model_contract_common_contExcelUtil :: tableThead(0, 1, '�б���Ϣ', $objPhpExcelFile);
	// model_contract_common_contExcelUtil :: toCecter(1, $objPhpExcelFile);
	// }
	//���ñ�ͷ����ʽ ����
	
	$theadColumn = 0;
	foreach ($thArr as $key => $val) {
		model_outsourcing_basic_export2ExcelUtil :: tableTrow($theadColumn, 1, $val, $objPhpExcelFile);
		$theadColumn++;

	}
	$i = 2;

	if (!count(array_filter($rowdatas)) == 0) {
		$row = $i;
		for ($n = 0; $n < count($rowdatas); $n++) {
			$m = 0;
			foreach ($rowdatas[$n] as $field => $value) {
				// $objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $row + $n, iconv("gb2312", "utf-8", $value));
				$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $n, mb_convert_encoding($value,"utf-8","gbk,big5"));
				$m++;
				//model_contract_common_contExcelUtil :: toCecter($i, $objPhpExcelFile);
			}
			$i++;
		}
	} else {
//		$objPhpExcelFile->getActiveSheet()->mergeCells('A' . $i . ':' . $highestColumn . $i);	
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
	// 		//$etime=microtime(true);//��ȡ����ִ�н�����ʱ��
	// 		//$total=$etime-$stime;   //�����ֵ
	// 		//echo "<br />{$total} times";
}
}
?>
<?php

/**
 * @description: ʵ�����ݵ�����Excel�Ĺ���
 * @date 2010-10-20 ����07:18:35
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

class model_hr_leave_leaveExportUtil {

	//ģ�����ݾ�����ʽ
	function toCecter($i, $objPHPExcel) {
		for($temp = 0; $temp < 21; $temp ++) {
			$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $temp, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		}
	}

	/**
	 * $cellValueΪ����Ķ�ά����
	 */
	public static function export2ExcelUtil($rowdatas) {
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		//		//����һ��Excel������
		//		$objPhpExcelFile = new PHPExcel();


		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //����һ��Excel�Ķ���
		$objPhpExcelFile = $objReader->load ( "upfile/��ְ��Ϣ�������.xls" ); //��ȡ�Զ���ģ��
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPhpExcelFile );

		//���ñ�����ļ����Ƽ�·��
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;

		//�����ǶԹ������������������
		//���õ�ǰ�����������
		$objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '��Ϣ�б�' ) );
		//���ñ�ͷ����ʽ ����
		$i = 3;//��ʼ������
//		echo "<pre>";
//		print_r($rowdatas[0]).die();
		if (! count ( array_filter ( $rowdatas ) ) == 0) {//��������������ݲ�Ϊ��
			$row = $i;//���ñ���ʼ������
			for($n = 0; $n < count ( $rowdatas ); $n ++) {//ȡ�����ݵ��ܸ���
				$m = 0;//��ʼ��ÿ�е�ÿһ��
				foreach ( $rowdatas [$n] as $field => $value ) {//���������ÿһ�е���ѭ����������
//					echo "<pre>";
//					print_r($row+$n);
					if($field=="state"){
						if ($value=="1"){
							$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row+$n, iconv ( "gb2312", "utf-8", "δȷ������" ) );
						}else if($value=="3"){
							$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row+$n, iconv ( "gb2312", "utf-8", "�Ѹ��µ���" ) );
						}else if($value=="4"){
							$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row+$n, iconv ( "gb2312", "utf-8", "�ѹر�" ) );
						}else{
							$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row+$n, iconv ( "gb2312", "utf-8", $value ) );
						}
							$m ++;
							continue;
					}
					if ($field=="userSelfCstatus"){
						if ($value=="WQR"){
							$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row+$n, iconv ( "gb2312", "utf-8", "δȷ��" ) );
						}else {
							$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row+$n, iconv ( "gb2312", "utf-8", "��ȷ��" ) );
						}
							$m++;
							continue;
					}
					if ($field=="handoverCstatus"){
						if ($value=="0"){
							$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row+$n, iconv ( "gb2312", "utf-8", "δ����" ) );
						}else if ($value=="WQR") {
							$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row+$n, iconv ( "gb2312", "utf-8", "δȷ��" ) );
						}else{
							$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row+$n, iconv ( "gb2312", "utf-8", "��ȷ��" ) );
						}
							$m++;
							continue;
					}
					if ($field=="softSate"){
						if ($value=="1"){
							$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row+$n, iconv ( "gb2312", "utf-8", "�ѹر�" ) );
						}else {
							$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row+$n, iconv ( "gb2312", "utf-8", "δ�ر�" ) );
						}
							$m++;
							continue;
					}
                    $objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row + $n,mb_convert_encoding($value,"utf-8","gbk,big5") );
					$m ++;
				}
				$i ++;
			}
		} else {
			$objPhpExcelFile->getActiveSheet ()->mergeCells ( 'A' . $i . ':' . 'W' . $i );
			for($m = 0; $m < 23; $m ++) {
				$objPhpExcelFile->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '���������Ϣ' ) );
			}
		}

		//�������
		ob_end_clean (); //��������������������������
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "��ְ��Ϣ�������.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}
}
?>
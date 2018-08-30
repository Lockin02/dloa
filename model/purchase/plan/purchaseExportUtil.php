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
include_once WEB_TOR . "model/contract/common/simple_html_dom.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/CachedObjectStorageFactory.php";

class model_purchase_plan_purchaseExportUtil {


	/**
	 * �ɹ�����
	 * ͨ��value����״̬
	 */
	function purchTypeToVal($purchVal) {
		$returnVal = false;

		 $purchaseType = array (
			0 => array (
				'purchCName' => '���۲ɹ�',
				'purchKey' => 'contract_sales',
			),
			1 => array (
				'purchCName' => '����ɹ�',
				'purchKey' => 'stock'
			),
			2 => array (
				'purchCName' => '�з��ɹ�',
				'purchKey' => 'rdproject'
			),
			3 => array (
				'purchCName' => '�ʲ��ɹ�',
				'purchKey' => 'assets'
			),
			4 => array (
				'purchCName' => '�����ɹ�',
				'purchKey' => 'order'
			),
			5 => array (
				'purchCName' => '��ͬ�ɹ�',
				'purchKey' => 'contract_sales'
			),
			6 => array (
				'purchCName' => '�����ɹ�',
				'purchKey' => 'produce'
			),
			7=> array (
				'purchCName' => '���ۺ�ͬ�ɹ�',
				'purchKey' => 'oa_sale_order'
			),
			8 => array (
				'purchCName' => '���޺�ͬ�ɹ�',
				'purchKey' => 'oa_sale_lease'
			),
			9 => array (
				'purchCName' => '�����ͬ�ɹ�',
				'purchKey' => 'oa_sale_service'
			),
			10 => array (
				'purchCName' => '�з���ͬ�ɹ�',
				'purchKey' => 'oa_sale_rdproject'
			),
			11 => array (
				'purchCName' => '�����òɹ�',
				'purchKey' => 'oa_borrow_borrow'
			),
			12 => array (
				'purchCName' => '���Ͳɹ�',
				'purchKey' => 'oa_present_present'
			),
			13=> array (
				'purchCName' => '���ۺ�ͬ�ɹ�',
				'purchKey' => 'HTLX-XSHT'
			),
			14 => array (
				'purchCName' => '���޺�ͬ�ɹ�',
				'purchKey' => 'HTLX-ZLHT'
			),
			15 => array (
				'purchCName' => '�����ͬ�ɹ�',
				'purchKey' => 'HTLX-FWHT'
			),
			16 => array (
				'purchCName' => '�з���ͬ�ɹ�',
				'purchKey' => 'HTLX-YFHT'
			)
		);
		foreach ( $purchaseType as $key => $val ) {
			if ($val ['purchKey'] == $purchVal) {
				$returnVal = $val ['purchCName'];
			}
		}
		//TODO:����쳣����
		return $returnVal;
	}

	//ģ�����ݾ�����ʽ
	function toCecter($i, $objPHPExcel) {
		for($temp = 0; $temp < 12; $temp ++) {
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


		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load ( "upfile/�ɹ������鵼��ģ��.xls" ); //��ȡģ��
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
		$i = 3;
		if (! count ( array_filter ( $rowdatas ) ) == 0) {
			$row = $i;
			for($n = 0; $n < count ( $rowdatas ); $n ++) {
				$m = 0;
				foreach ( $rowdatas [$n] as $field => $value ) {
					$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $row + $n, iconv ( "gb2312", "utf-8", $value ) );
					$m ++;

		//model_contract_common_contExcelUtil :: toCecter($i, $objPhpExcelFile);
				}
				$i ++;
			}
		} else {
			$objPhpExcelFile->getActiveSheet ()->mergeCells ( 'A' . $i . ':' . 'H' . $i );
			for($m = 0; $m < 8; $m ++) {
				$objPhpExcelFile->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '���������Ϣ' ) );
			}
		}

		//�������
		ob_end_clean (); //��������������������������
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "�ɹ������б�����Ϣ.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}

	/**
	 * $cellValueΪ����Ķ�ά����
	 */
	public static function exportAging_e($rowdatas) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/�ɹ�����ʱЧ��.xls" ); //��ȡģ��
		$excelActiveSheet = $objPHPExcel->getActiveSheet ();
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
		$i = 1;

		//����
		$week = array ('����һ', '���ڶ�', '������', '������', '������', '������', '������' );

		//�������
		$exaResult = array ('ok' => 'ͨ��', 'no' => '��ͨ��' );

		//�ɹ�����
		$purchTypeArr = array ('contract_sales' => '��ͬ�ɹ�', 'contract_sales_equ' => '��ͬ�ɹ�', 'stock' => '����ɹ�', 'assets' => '�ʲ��ɹ�', 'rdproject' => '�з��ɹ�', 'order' => '�����ɹ�', 'produce' => '�����ɹ�', 'oa_sale_order' => '���ۺ�ͬ�ɹ�', 'oa_sale_lease' => '���޺�ͬ�ɹ�', 'oa_sale_service' => '�����ͬ�ɹ�', 'oa_sale_rdproject' => '�з���ͬ�ɹ�', 'oa_borrow_borrow' => '����ɹ�', 'oa_present_present' => '����ɹ�', 'oa_asset_purchase_apply' => '�ʲ��ɹ�' );
		//		echo "<pre>";
		//		print_r($rowdatas);
		//		return false;
		foreach ( $rowdatas as $key => $val ) { //�´�ɹ�����
			//��Ա������������
			$useDayArr = array();
			//�´�ɹ�����
			++ $i;
			$objPHPExcel->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( 0, $i, iconv ( "gb2312", "utf-8", $val ['planCode'] ), PHPExcel_Cell_DataType::TYPE_STRING );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $i, iconv ( "gb2312", "utf-8", '�ۺϾ���' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $i, iconv ( "gb2312", "utf-8", $val ['planCreateBy'] ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $i, iconv ( "gb2312", "utf-8", '�´�ɹ�����' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $i, iconv ( "gb2312", "utf-8", $val ['planSendDate'] . ' ' . $week [$val ['planSendDay']] ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $i, iconv ( "gb2312", "utf-8", $val ['planMonth'] * 1 . '��' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, $i, iconv ( "gb2312", "utf-8", $purchTypeArr [$val ['purchType']] ) );
			$objPHPExcel->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( 8, $i, iconv ( "gb2312", "utf-8", $val ['sourceNumb'] ), PHPExcel_Cell_DataType::TYPE_STRING );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, $i, iconv ( "gb2312", "utf-8", $val ['customerName'] ) );

			//���ղɹ�����
			++ $i;
			$objPHPExcel->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( 0, $i, iconv ( "gb2312", "utf-8", $val ['planCode'] ), PHPExcel_Cell_DataType::TYPE_STRING );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $i, iconv ( "gb2312", "utf-8", '�ɹ�����' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $i, iconv ( "gb2312", "utf-8", $val ['taskCreateBy'] ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $i, iconv ( "gb2312", "utf-8", '���ղɹ�����' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $i, iconv ( "gb2312", "utf-8", $val ['planSendDate'] . ' ' . $week [$val ['planSendDay']] ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i, iconv ( "gb2312", "utf-8", 0 .'��' ) );

			if (empty ( $val ['taskSendDate'] )) {
				//�����������
//				$date1 = strtotime ( $val ['planSendDate'] );
//				$date2 = strtotime ( $val ['planSendDate'] );
//				$days = ($date2 - $date1) / 86400;
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i - 1, iconv ( "gb2312", "utf-8", '��������0��' ) );

				//������Ա��ʱ
				$k = count($useDayArr);
				foreach($useDayArr as $key => $val){
					$k -- ;
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, $i - 1 + $k , iconv ( "gb2312", "utf-8",  $key.'��' .$val . '��' ) );
				}

				++ $i;
				continue;
			}
			//����ɹ�����
			++ $i;
			$objPHPExcel->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( 0, $i, iconv ( "gb2312", "utf-8", $val ['planCode'] ), PHPExcel_Cell_DataType::TYPE_STRING );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $i, iconv ( "gb2312", "utf-8", '�ɹ�����' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $i, iconv ( "gb2312", "utf-8", $val ['taskCreateBy'] ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $i, iconv ( "gb2312", "utf-8", '����ɹ�����' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $i, iconv ( "gb2312", "utf-8", $val ['taskSendDate'] . ' ' . $week [$val ['taskSendDay']] ) );

			//���㵥��������
			$date1 = strtotime ( $val ['planSendDate'] );
			$date2 = strtotime ( $val ['taskSendDate'] );
			$stepDay = ($date2 - $date1) / 86400;
			//������Ա��������
			$useDayArr[$val ['taskCreateBy']] = $stepDay;

			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i, iconv ( "gb2312", "utf-8", $stepDay .'��' ) );

			if (empty ( $val ['taskEndDate'] )) {
				//�����������
//				$date1 = strtotime ( $val ['planSendDate'] );
//				$date2 = strtotime ( $val ['taskSendDate'] );
//				$days = ($date2 - $date1) / 86400;
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i - 2, iconv ( "gb2312", "utf-8",  '��������' .$stepDay . '��' ) );

				//������Ա��ʱ
				$k = count($useDayArr);
				foreach($useDayArr as $key => $val){
					$k -- ;
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, $i - 2 + $k , iconv ( "gb2312", "utf-8",  $key.'��' .$val . '��' ) );
				}

				++ $i;
				continue;
			}
			//���ղɹ�����
			++ $i;
			$objPHPExcel->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( 0, $i, iconv ( "gb2312", "utf-8", $val ['taskNo'] ), PHPExcel_Cell_DataType::TYPE_STRING );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $i, iconv ( "gb2312", "utf-8", '�ɹ�Ա' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $i, iconv ( "gb2312", "utf-8", $val ['taskSendName'] ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $i, iconv ( "gb2312", "utf-8", '���ղɹ�����' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $i, iconv ( "gb2312", "utf-8", $val ['taskEndDate'] . ' ' . $week [$val ['taskEndDay']] ) );

			//���㵥��������
			$date1 = strtotime ( $val ['taskSendDate'] );
			$date2 = strtotime ( $val ['taskEndDate'] );
			$stepDay = ($date2 - $date1) / 86400;
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i, iconv ( "gb2312", "utf-8", $stepDay .'��' ) );
			//������Ա��������
			if(isset($useDayArr[$val ['taskSendName']])){
				$useDayArr[$val ['taskSendName']] = $useDayArr[$val ['taskSendName']] + $stepDay;
			}else{
				$useDayArr[$val ['taskSendName']] = $stepDay;
			}

			if (empty ( $val ['inquiryBeginDate'] )) {
				//�����������
				$date1 = strtotime ( $val ['planSendDate'] );
				$date2 = strtotime ( $val ['taskEndDate'] );
				$days = ($date2 - $date1) / 86400;
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i - 3, iconv ( "gb2312", "utf-8", '��������' . $days . '��' ) );

				//������Ա��ʱ
				$k = count($useDayArr);
				foreach($useDayArr as $key => $val){
					$k -- ;
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, $i - 3 + $k , iconv ( "gb2312", "utf-8",  $key.'��' .$val . '��' ) );
				}

				++ $i;
				continue;
			}
			//���ѯ������
			++ $i;
			$objPHPExcel->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( 0, $i, iconv ( "gb2312", "utf-8", $val ['inquiryNo'] ), PHPExcel_Cell_DataType::TYPE_STRING );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $i, iconv ( "gb2312", "utf-8", '�ɹ�Ա' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $i, iconv ( "gb2312", "utf-8", $val ['inquiryCreateBy'] ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $i, iconv ( "gb2312", "utf-8", '���ѯ������' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $i, iconv ( "gb2312", "utf-8", $val ['inquiryBeginDate'] . ' ' . $week [$val ['inquiryBeginDay']] ) );

			//���㵥��������
			$date1 = strtotime ( $val ['taskEndDate'] );
			$date2 = strtotime ( $val ['inquiryBeginDate'] );
			$stepDay = ($date2 - $date1) / 86400;
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i, iconv ( "gb2312", "utf-8", $stepDay .'��' ) );
			//������Ա��������
			if(isset($useDayArr[$val ['inquiryCreateBy']])){
				$useDayArr[$val ['inquiryCreateBy']] = $useDayArr[$val ['inquiryCreateBy']] + $stepDay;
			}else{
				$useDayArr[$val ['inquiryCreateBy']] = $stepDay;
			}

			if (empty ( $val ['inquiryEndDate'] )) {
				//�����������
				$date1 = strtotime ( $val ['planSendDate'] );
				$date2 = strtotime ( $val ['inquiryBeginDate'] );
				$days = ($date2 - $date1) / 86400;
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i - 4, iconv ( "gb2312", "utf-8",  '��������' .$days . '��' ) );

				//������Ա��ʱ
				$k = count($useDayArr);
				foreach($useDayArr as $key => $val){
					$k -- ;
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, $i - 4 + $k , iconv ( "gb2312", "utf-8",  $key.'��' .$val . '��' ) );
				}

				++ $i;
				continue;
			}
			//�����ɹ�ѯ��
			++ $i;
			$objPHPExcel->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( 0, $i, iconv ( "gb2312", "utf-8", $val ['inquiryNo'] ), PHPExcel_Cell_DataType::TYPE_STRING );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $i, iconv ( "gb2312", "utf-8", '�ɹ�����' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $i, iconv ( "gb2312", "utf-8", $val ['inquiryExaBy'] ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $i, iconv ( "gb2312", "utf-8", '�����ɹ�ѯ��' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $i, iconv ( "gb2312", "utf-8", $val ['inquiryEndDate'] . ' ' . $week [$val ['inquiryEndDay']] ) );

			//���㵥��������
			$date1 = strtotime ( $val ['inquiryBeginDate'] );
			$date2 = strtotime ( $val ['inquiryEndDate'] );
			$stepDay = ($date2 - $date1) / 86400;
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i, iconv ( "gb2312", "utf-8", $stepDay .'��' ) );
			//������Ա��������
			if(isset($useDayArr[$val ['inquiryExaBy']])){
				$useDayArr[$val ['inquiryExaBy']] = $useDayArr[$val ['inquiryExaBy']] + $stepDay;
			}else{
				$useDayArr[$val ['inquiryExaBy']] = $stepDay;
			}

			if (empty ( $val ['applyBeginDate'] )) {
				//�����������
				$date1 = strtotime ( $val ['planSendDate'] );
				$date2 = strtotime ( $val ['inquiryEndDate'] );
				$days = ($date2 - $date1) / 86400;
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i - 5, iconv ( "gb2312", "utf-8",  '��������' .$days . '��' ) );

				//������Ա��ʱ
				$k = count($useDayArr);
				foreach($useDayArr as $key => $val){
					$k -- ;
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, $i - 5 + $k , iconv ( "gb2312", "utf-8",  $key.'��' .$val . '��' ) );
				}

				++ $i;
				continue;
			}
			//�ɹ���������
			++ $i;
			$objPHPExcel->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( 0, $i, iconv ( "gb2312", "utf-8", $val ['applyNo'] ), PHPExcel_Cell_DataType::TYPE_STRING );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $i, iconv ( "gb2312", "utf-8", '�ɹ�Ա' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $i, iconv ( "gb2312", "utf-8", $val ['applyCreateBy'] ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $i, iconv ( "gb2312", "utf-8", '����ɹ�����' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $i, iconv ( "gb2312", "utf-8", $val ['applyBeginDate'] . ' ' . $week [$val ['applyBeginDay']] ) );

			//���㵥��������
			$date1 = strtotime ( $val ['inquiryEndDate'] );
			$date2 = strtotime ( $val ['applyBeginDate'] );
			$stepDay = ($date2 - $date1) / 86400;
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i, iconv ( "gb2312", "utf-8", $stepDay .'��' ) );
			//������Ա��������
			if(isset($useDayArr[$val ['applyCreateBy']])){
				$useDayArr[$val ['applyCreateBy']] = $useDayArr[$val ['applyCreateBy']] + $stepDay;
			}else{
				$useDayArr[$val ['applyCreateBy']] = $stepDay;
			}

			if (empty ( $val ['applyExaBy'] )) {
				//�����������
				$date1 = strtotime ( $val ['planSendDate'] );
				$date2 = strtotime ( $val ['applyBeginDate'] );
				$days = ($date2 - $date1) / 86400;
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i - 6, iconv ( "gb2312", "utf-8",  '��������' .$days . '��' ) );

				//������Ա��ʱ
				$k = count($useDayArr);
				foreach($useDayArr as $key => $val){
					$k -- ;
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, $i - 6 + $k , iconv ( "gb2312", "utf-8",  $key.'��' .$val . '��' ) );
				}

				++ $i;
				continue;
			}
			//�ɹ���������
			++ $i;
			$objPHPExcel->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( 0, $i, iconv ( "gb2312", "utf-8", $val ['applyNo'] ), PHPExcel_Cell_DataType::TYPE_STRING );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $i, iconv ( "gb2312", "utf-8", '�ɹ�����' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $i, iconv ( "gb2312", "utf-8", $val ['applyExaBy'] ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $i, iconv ( "gb2312", "utf-8", '�ɹ���������' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $i, iconv ( "gb2312", "utf-8", $val ['applyEndDate'] . ' ' . $week [$val ['applyEndDay']] ) );

			//���㵥��������
			$date1 = strtotime ( $val ['applyBeginDate'] );
			$date2 = strtotime ( $val ['applyEndDate'] );
			$stepDay = ($date2 - $date1) / 86400;
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i, iconv ( "gb2312", "utf-8", $stepDay .'��' ) );
			//������Ա��������
			if(isset($useDayArr[$val ['applyExaBy']])){
				$useDayArr[$val ['applyExaBy']] = $useDayArr[$val ['applyExaBy']] + $stepDay;
			}else{
				$useDayArr[$val ['applyExaBy']] = $stepDay;
			}

			//�����������
			$date1 = strtotime ( $val ['planSendDate'] );
			$date2 = strtotime ( $val ['applyEndDate'] );
			$days = ($date2 - $date1) / 86400;
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i - 7, iconv ( "gb2312", "utf-8",  '��������' .$days . '��' ) );

			//������Ա��ʱ
			$k = count($useDayArr);
			foreach($useDayArr as $key => $val){
				$k -- ;
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, $i - 7 + $k , iconv ( "gb2312", "utf-8",  $key.'��' .$val . '��' ) );
			}

			++ $i;
		}
//		//�������
		ob_end_clean (); //��������������������������
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "�ɹ�����ʱЧ��.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}

	/**
	 * ����ͬ����������Ϣ
	 *
	 */
	function exportProduceEqu_e($dataArr, $batchNumb) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/�����κŵ���ģ��.xls" ); //��ȡģ��
		$excelActiveSheet = $objPHPExcel->getActiveSheet ();

		for($n = 0; $n < count ( $dataArr ); $n ++) {

			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 0, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['batchNumb'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 1, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['dateIssued'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 2, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['basicNumb'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 3, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['ExaStatus'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 4, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['sendName'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 5, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productNumb'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 6, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 7, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productTypeName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 8, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['pattem'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 9, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['unitName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 10, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['amountAll'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 11, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['amountIssued'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 12, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['dateHope'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 13, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['stockNumbTotal'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 14, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['arrivalNum'] ) );
		}

		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
		//�������
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "�ɹ�����-$batchNumb.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );

	}

	/**
	 * �����ɹ�����������Ϣ
	 *
	 */
	function exportPlanEqu_e($dataArr) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/�ɹ����뵼��ģ��.xls" ); //��ȡģ��
		$excelActiveSheet = $objPHPExcel->getActiveSheet ();

		 $thisDao=new model_purchase_plan_purchaseExportUtil();
		for($n = 0; $n < count ( $dataArr ); $n ++) {
			$purchTypeName=$thisDao->purchTypeToVal($dataArr [$n] ['purchType']);

			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 0, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['sendTime'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 1, $n + 2, iconv ( "GBK", "utf-8", $purchTypeName ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 2, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['sourceNumb'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 3, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['sendName'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 4, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productNumb'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 5, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 6, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productTypeName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 7, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['pattem'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 8, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['unitName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 9, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['amountAll'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 10, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['dateHope'] ) );
		}

		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
		//�������
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "�ɹ�����������Ϣ.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );

	}


	/**
	 * �����ѹرղɹ�����������Ϣ
	 *
	 */
	function exportTaskCloseEqu_e($dataArr) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/�ѹرղɹ��������ϻ���.xls" ); //��ȡģ��
		$excelActiveSheet = $objPHPExcel->getActiveSheet ();

		for($n = 0; $n < count ( $dataArr ); $n ++) {

			$excelActiveSheet->setCellValueByColumnAndRow ( 0, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['sendTime'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 1, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['dateReceive'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 2, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productNumb'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 3, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 4, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['planNumb'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 5, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['sendName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 6, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['amountAll'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 7, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['amountIssued'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 8, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['contractAmount'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 9, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['planSendName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 10, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['department'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 11, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['dateFact'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 12, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['closeRemark'] ) );
		}

		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
		//�������
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "�ѹرղɹ��������ϻ���.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );

	}

	/**
	 * �����ɹ���������ִ����Ϣ
	 *
	 */
	function exportTaskEqu_e($dataArr) {
		$orderEquDao = new model_purchase_contract_equipment ();
		$inquiryEquDao = new model_purchase_inquiry_equmentInquiry ();
		$inquiryDao = new model_purchase_inquiry_inquirysheet ();
		$arrivalEquDao = new model_purchase_arrival_equipment ();
		$taskDao=new model_purchase_task_basic();
		$payDao=new model_finance_payables_detail();
			$orderDao=new model_purchase_contract_purchasecontract();
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/�ɹ�����ִ���������.xls" ); //��ȡģ��
		$excelActiveSheet = $objPHPExcel->getActiveSheet ();
		$nowRow = 2; //�ɹ���������


		for($n = 0; $n < count ( $dataArr ); $n ++) {
			if (is_array ( $dataArr [$n] )) {//��ȡ����������Ϣ
				$orderEquRows = $orderEquDao->getProgressEqusByTequId ( $dataArr [$n] ['id'] );
			}
			$taskState=$taskDao->statusDao->statusKtoC( $dataArr [$n]['state']);
			$seNum = 0; //�ɹ���������
			if (count ( $orderEquRows ) > 1) {
				for($j = 1; $j < count ( $orderEquRows ) + 1; $j ++) {
					if (count ( $orderEquRows ) > 1) {
						for($j = 1; $j < count ( $orderEquRows ); $j ++) {
							$arrivalEquRows=$arrivalEquDao->getItemByContractEquId_d( $orderEquRows [$j]['id']);
							$payed=$payDao->getPayedMoney_d( $orderEquRows [$j]['id'],$objType = 'YFRK-01');
							$stateName=$orderDao->stateToVal ( $orderEquRows [$j] ['state']);
							if($payed==0){
								$payStr='δ��';
							}else if($payed== $orderEquRows [$j]['moneyAll']){
								$payStr='�Ѹ�';
							}else{
								$payStr='����';
							}
							$arrivalNum=0;
							if(is_array($arrivalEquRows)){   //��ȡĳ���ϵ��������
								foreach($arrivalEquRows as $arrKey=>$arrVal){
									$arrivalNum=$arrivalNum+$arrVal['arrivalNum'];
								}
							}
							$excelActiveSheet->setCellValueByColumnAndRow ( 8, $nowRow + $seNum + $j - 1, iconv ( "GBK", "utf-8", $orderEquRows [$j] ['amountAll'] ) ); //��������
							$excelActiveSheet->setCellValueByColumnAndRow ( 9, $nowRow + $seNum + $j - 1, iconv ( "GBK", "utf-8", $orderEquRows [$j] ['ExaStatus'] ) ); //��������״̬
							$excelActiveSheet->setCellValueByColumnAndRow (10, $nowRow + $seNum + $j - 1, iconv ( "GBK", "utf-8", $stateName ) ); //����״̬
							$excelActiveSheet->setCellValueByColumnAndRow ( 11, $nowRow + $seNum + $j - 1, iconv ( "GBK", "utf-8", $arrivalNum ) ); //��������
							$excelActiveSheet->setCellValueByColumnAndRow ( 12, $nowRow + $seNum + $j - 1, iconv ( "GBK", "utf-8", $orderEquRows [$j] ['amountIssued'] ) ); //�������
							$excelActiveSheet->setCellValueByColumnAndRow ( 13, $nowRow + $seNum + $j - 1, iconv ( "GBK", "utf-8", $payStr ) ); //�������
						}
						$seNum += count ( $orderEquRows );
					} else {
						$seNum += 1;
					}
					$arrivalEquRows1=$arrivalEquDao->getItemByContractEquId_d( $orderEquRows [0]['id']);
					$payed1=$payDao->getPayedMoney_d( $orderEquRows [0]['id'],$objType = 'YFRK-01');
					$stateName1=$orderDao->stateToVal ( $orderEquRows [0] ['state']);
					if($payed1==0){
						$payStr1='δ��';
					}else if($payed1== $orderEquRows [0]['moneyAll']){
						$payStr1='�Ѹ�';
					}else{
						$payStr1='����';
					}
					$arrivalNum1=0;
					if(is_array($arrivalEquRows1)){   //��ȡĳ���ϵ��������
						foreach($arrivalEquRows1 as $arrKey=>$arrVal){
							$arrivalNum1=$arrivalNum1+$arrVal['arrivalNum'];
						}
					}

					//���˲ɹ�ѯ���к� �ĵڶ�����Ϣ
					$excelActiveSheet->setCellValueByColumnAndRow ( 8, $nowRow + $seNum - 1, iconv ( "GBK", "utf-8", $orderEquRows [0] ['amountAll'] ) ); //��������
					$excelActiveSheet->setCellValueByColumnAndRow ( 9, $nowRow + $seNum - 1, iconv ( "GBK", "utf-8", $orderEquRows [0] ['ExaStatus'] ) ); //��������״̬
					$excelActiveSheet->setCellValueByColumnAndRow ( 10, $nowRow + $seNum - 1, iconv ( "GBK", "utf-8", $stateName1) ); //����״̬
					$excelActiveSheet->setCellValueByColumnAndRow ( 11, $nowRow + $seNum - 1, iconv ( "GBK", "utf-8", $arrivalNum1 ) ); //��������
					$excelActiveSheet->setCellValueByColumnAndRow ( 12, $nowRow + $seNum - 1, iconv ( "GBK", "utf-8", $orderEquRows [0] ['amountIssued'] ) ); //�������
					$excelActiveSheet->setCellValueByColumnAndRow ( 13, $nowRow + $seNum - 1, iconv ( "GBK", "utf-8", $payStr1 ) ); //�������
				}
			} else {
					if(count( $orderEquRows )>0){
						$arrivalEquRows=$arrivalEquDao->getItemByContractEquId_d( $orderEquRows [0]['id']);
						$payed=$payDao->getPayedMoney_d( $orderEquRows [0]['id'],$objType = 'YFRK-01');
						$stateName=$orderDao->stateToVal ( $orderEquRows [0] ['state']);
						if($payed==0){
							$payStr='δ��';
						}else if($payed== $orderEquRows [0]['moneyAll']){
							$payStr='�Ѹ�';
						}else{
							$payStr='����';
						}
						$arrivalNum=0;
						if(is_array($arrivalEquRows)){   //��ȡĳ���ϵ��������
							foreach($arrivalEquRows as $arrKey=>$arrVal){
								$arrivalNum=$arrivalNum+$arrVal['arrivalNum'];
							}
						}
						$excelActiveSheet->setCellValueByColumnAndRow ( 8, $nowRow + $seNum , iconv ( "GBK", "utf-8", $orderEquRows [0] ['amountAll'] ) ); //��������
						$excelActiveSheet->setCellValueByColumnAndRow ( 9, $nowRow + $seNum , iconv ( "GBK", "utf-8", $orderEquRows [0] ['ExaStatus'] ) ); //��������״̬
						$excelActiveSheet->setCellValueByColumnAndRow ( 10, $nowRow + $seNum , iconv ( "GBK", "utf-8", $stateName ) ); //����״̬
						$excelActiveSheet->setCellValueByColumnAndRow ( 11, $nowRow + $seNum , iconv ( "GBK", "utf-8", $arrivalNum ) ); //��������
						$excelActiveSheet->setCellValueByColumnAndRow ( 12, $nowRow + $seNum , iconv ( "GBK", "utf-8", $orderEquRows [0] ['amountIssued'] ) ); //�������
						$excelActiveSheet->setCellValueByColumnAndRow ( 13, $nowRow + $seNum , iconv ( "GBK", "utf-8", $payStr) ); //�������

					}
					$seNum += 1;
			}

			//�ϲ��ɹ�����
			for($t = 0; $t < ($seNum - 1); $t ++) {
				$excelActiveSheet->mergeCellsByColumnAndRow ( 0, $nowRow + $t, 0, $nowRow + $t + 1 );
				$excelActiveSheet->mergeCellsByColumnAndRow ( 1, $nowRow + $t, 1, $nowRow + $t + 1 );
				$excelActiveSheet->mergeCellsByColumnAndRow ( 2, $nowRow + $t, 2, $nowRow + $t + 1 );
				$excelActiveSheet->mergeCellsByColumnAndRow ( 3, $nowRow + $t, 3, $nowRow + $t + 1 );
				$excelActiveSheet->mergeCellsByColumnAndRow ( 4, $nowRow + $t, 4, $nowRow + $t + 1 );
				$excelActiveSheet->mergeCellsByColumnAndRow ( 5, $nowRow + $t, 5, $nowRow + $t + 1 );
				$excelActiveSheet->mergeCellsByColumnAndRow ( 6, $nowRow + $t, 6, $nowRow + $t + 1 );
				$excelActiveSheet->mergeCellsByColumnAndRow ( 7, $nowRow + $t, 7, $nowRow + $t + 1 );
			}

			//��һ����Ϣ
			$excelActiveSheet->setCellValueByColumnAndRow ( 0, $nowRow, iconv ( "GBK", "utf-8", $dataArr [$n] ['sendTime'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 1, $nowRow, iconv ( "GBK", "utf-8", $dataArr [$n] ['dateReceive'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 2, $nowRow, iconv ( "GBK", "utf-8", $dataArr [$n] ['productNumb'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 3, $nowRow, iconv ( "GBK", "utf-8", $dataArr [$n] ['productName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 4, $nowRow, iconv ( "GBK", "utf-8", $dataArr [$n] ['planNumb'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 5, $nowRow, iconv ( "GBK", "utf-8", $dataArr [$n] ['sendName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 6, $nowRow, iconv ( "GBK", "utf-8", $dataArr [$n] ['amountAll'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow (7, $nowRow, iconv ( "GBK", "utf-8", $taskState) );

			$nowRow += $seNum;
		}

		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
		//�������
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "�ɹ�����ִ�����.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );

	}
}
?>
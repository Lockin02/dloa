<?php
/*
 * Created on 2012-6-7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include_once "module/phpExcel/classes/PHPExcel.php";
include_once "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include "module/phpExcel/Classes/PHPExcel/Cell.php";
include_once "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include_once "module/phpExcel/Classes/PHPExcel/Reader/Excel2007.php";
include_once "module/phpExcel/Classes/PHPExcel/ReferenceHelper.php";
include_once "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include_once "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
include ('model/contract/common/simple_html_dom.php');
include_once "module/phpExcel/Classes/PHPExcel/CachedObjectStorageFactory.php";

class model_contract_common_contractExcelUtil {

	//ģ������ʽ
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

	/**
	 * ������ͬ����
	 * 2012-06-07
	 */
	public static function otherContractOut_e($rowdatas) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/saleOther_ExportTmp.xls" ); //��ȡģ��
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );

		//ǩ��״̬
		$signArr = array('δǩ��','��ǩ��');

		//��ͬ״̬
		$statusArr = array("δ�ύ","������","ִ����","�ѹر�","�����");

		//����״̬
		$stampArr = array("��","��");

		foreach( $rowdatas as $key => $val){
			$insertRow = 2+$key;
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(0,$insertRow,iconv("GBK","utf-8",$val['createDate']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(1,$insertRow,iconv("GBK","utf-8",$val['fundTypeName']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(2,$insertRow,iconv("GBK","utf-8",$val['orderCode']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(3,$insertRow,iconv("GBK","utf-8",$val['orderName']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(4,$insertRow,iconv("GBK","utf-8",$val['signCompanyName']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(5,$insertRow,iconv("GBK","utf-8",$val['proName']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(6,$insertRow,iconv("GBK","utf-8",$val['linkman']));
			$objPHPExcel->getActiveSheet ()->setCellValueExplicitByColumnAndRow(7,$insertRow,iconv("GBK","utf-8",$val['phone']),PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(8,$insertRow,iconv("GBK","utf-8",$val['address']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(9,$insertRow,iconv("GBK","utf-8",$val['signDate']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(10,$insertRow,iconv("GBK","utf-8",$val['principalName']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(11,$insertRow,iconv("GBK","utf-8",$val['deptName']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(12,$insertRow,iconv("GBK","utf-8",$val['createName']));
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(13,$insertRow,iconv("GBK","utf-8",$val['currency']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(14,$insertRow,iconv("GBK","utf-8",$val['orderMoney']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(15,$insertRow,iconv("GBK","utf-8",$val['payApplyMoney']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(16,$insertRow,iconv("GBK","utf-8",$val['payedMoney']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(17,$insertRow,iconv("GBK","utf-8",$val['returnMoney']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(18,$insertRow,iconv("GBK","utf-8",$val['invotherMoney']));
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(19,$insertRow,iconv("GBK","utf-8",$val['confirmInvotherMoney']));
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(20,$insertRow,iconv("GBK","utf-8",$val['needInvotherMoney']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(21,$insertRow,iconv("GBK","utf-8",$val['applyInvoice']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(22,$insertRow,iconv("GBK","utf-8",$val['invoiceMoney']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(23,$insertRow,iconv("GBK","utf-8",$val['incomeMoney']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(24,$insertRow,iconv("GBK","utf-8",$statusArr[$val['status']]));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(25,$insertRow,iconv("GBK","utf-8",$signArr[$val['signedStatus']]));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(26,$insertRow,iconv("GBK","utf-8",$stampArr[$val['isStamp']]));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(27,$insertRow,iconv("GBK","utf-8",$val['stampType']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(28,$insertRow,iconv("GBK","utf-8",$val['businessBelongName']));

            // ��֤�������ֶ�
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(29,$insertRow,iconv("GBK","utf-8",$val['payForBusinessName']));
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(30,$insertRow,iconv("GBK","utf-8",$val['chanceCode']));
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(31,$insertRow,iconv("GBK","utf-8",$val['prefBidDate']));
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(32,$insertRow,iconv("GBK","utf-8",$val['contractCode']));
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(33,$insertRow,iconv("GBK","utf-8",$val['projectPrefEndDate']));
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(34,$insertRow,iconv("GBK","utf-8",$val['delayPayDays']));

            $isBankbackLetter = "";
            if($val['isBankbackLetter'] == 1){
                $isBankbackLetter = "��";
            }else if($val['payForBusiness'] == "FKYWLX-03" || $val['payForBusiness'] == "FKYWLX-04"){
                $isBankbackLetter = "��";
            }
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(35,$insertRow,iconv("GBK","utf-8",$isBankbackLetter));

            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(36,$insertRow,iconv("GBK","utf-8",$val['backLetterEndDate']));
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(37,$insertRow,iconv("GBK","utf-8",$val['prefPayDate']));
		}
		//�������
        ob_end_clean();//��������������������������
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "������ͬ����.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}
	
	function outsourcingContractOut_e($rowdatas){
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/�����ͬ����ģ��.xls" ); //��ȡģ��
		$excelActiveSheet=$objPHPExcel->getActiveSheet ();
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
		
		foreach( $rowdatas as $key => $val){
			$insertRow = 2+$key;
			
			//״̬
			$statusArr = array("δ�ύ","������","ִ����","�ѹر�","�����");
			
			//ǩ��״̬
			$signArr = array('δǩ��','��ǩ��');
			
			//����״̬
			$stampArr = array("��","��");
			
			if($val['allCount']==null){
				$val['allCount']=0;
			}else if($val['allCount'] != 0){
				$val['allCount'] = number_format($val['allCount'],2);
			}
			if($val['applyedMoney']==null){
				$val['applyedMoney']=0;
			}else if($val['applyedMoney'] != 0){
				$val['applyedMoney'] = number_format($val['applyedMoney'],2);
			}
			if($val['payedMoney']==null){
				$val['payedMoney']=0;
			}else if($val['payedMoney'] != 0){
				$val['payedMoney'] = number_format($val['payedMoney'],2);
			}
			
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(0,$insertRow,iconv("GBK","utf-8",$val['createDate']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(1,$insertRow,iconv("GBK","utf-8",$val['orderCode']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(2,$insertRow,iconv("GBK","utf-8",$val['orderName']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(3,$insertRow,iconv("GBK","utf-8",$val['signCompanyName']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(4,$insertRow,iconv("GBK","utf-8",$val['outContractCode']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(5,$insertRow,iconv("GBK","utf-8",$val['businessBelongName']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(6,$insertRow,iconv("GBK","utf-8",$val['signDate']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(7,$insertRow,iconv("GBK","utf-8",$val['beginDate']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(8,$insertRow,iconv("GBK","utf-8",$val['endDate']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(9,$insertRow,iconv("GBK","utf-8",$val['orderMoney']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(10,$insertRow,iconv("GBK","utf-8",$val['allCount']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(11,$insertRow,iconv("GBK","utf-8",$val['applyedMoney']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(12,$insertRow,iconv("GBK","utf-8",$val['payedMoney']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(13,$insertRow,iconv("GBK","utf-8",$val['payTypeName']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(14,$insertRow,iconv("GBK","utf-8",$val['outsourcingName']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(15,$insertRow,iconv("GBK","utf-8",$val['outsourceTypeName']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(16,$insertRow,iconv("GBK","utf-8",$val['projectCode']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(17,$insertRow,iconv("GBK","utf-8",$val['deptName']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(18,$insertRow,iconv("GBK","utf-8",$val['principalName']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(19,$insertRow,iconv("GBK","utf-8",$statusArr[$val['status']]));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(20,$insertRow,iconv("GBK","utf-8",$val['ExaStatus']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(21,$insertRow,iconv("GBK","utf-8",$signArr[$val['signedStatus']]));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(22,$insertRow,iconv("GBK","utf-8",$val['objCode']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(23,$insertRow,iconv("GBK","utf-8",$stampArr[$val['isNeedStamp']]));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(24,$insertRow,iconv("GBK","utf-8",$stampArr[$val['isStamp']]));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(25,$insertRow,iconv("GBK","utf-8",$val['stampType']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(26,$insertRow,iconv("GBK","utf-8",$val['createName']));
		}
		//�������
		ob_end_clean();//��������������������������
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "�����ͬ����.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}



	//��ȡģ�岢����
	public static function exporTemplate($dataArr, $rowdatas, $imageName) {
//		echo 11111111;
//		echo "<pre>";
//		print_R($dataArr);
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/contractExport.xls" ); //��ȡģ��
		$excelActiveSheet = $objPHPExcel->getActiveSheet ();
		//���ñ�����ļ����Ƽ�·��
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;
		$objPhpExcelFile = new PHPExcel ();

		//���ñ�ͷ����ʽ ����
		for($m = 0; $m < 12; $m ++) {
			model_contract_common_contractExcelUtil::tableThead ( $m, 1, '��ͬ��Ϣ', $objPHPExcel );
		}
		foreach ( $dataArr as $key => $val ) {
			if ($val === null) {
				$dataArr [$key] [$val] = '';
			}
		}
		//��ȡ�ӱ�����
		//��������
		$tempSequence = 1;
		foreach ( $dataArr ['payment'] as $sequence => $equ ) {
			$payment [$sequence] ['sequence'] = $tempSequence;
			$payment [$sequence] ['paymentterm'] = $dataArr ['payment'] [$sequence] ['paymentterm'];
			$payment [$sequence] ['paymentPer'] = $dataArr ['payment'] [$sequence] ['paymentPer'];
			$payment [$sequence] ['money'] = $dataArr ['payment'] [$sequence] ['money'];
			$payment [$sequence] ['payDT'] = $dataArr ['payment'] [$sequence] ['payDT'];
			$payment [$sequence] ['remark'] = $dataArr ['payment'] [$sequence] ['remark'];
			$tempSequence ++;
		}
		//��ͬ��ϵ��
		$tempSequence = 1;
		foreach ( $dataArr ['linkman'] as $sequence => $equ ) {
			$linkman [$sequence] ['sequence'] = $tempSequence;
			$linkman [$sequence] ['linkmanName'] = $dataArr ['linkman'] [$sequence] ['linkmanName'];
			$linkman [$sequence] ['telephone'] = $dataArr ['linkman'] [$sequence] ['telephone'];
			$linkman [$sequence] ['QQ'] = $dataArr ['linkman'] [$sequence] ['QQ'];
			$linkman [$sequence] ['Email'] = $dataArr ['linkman'] [$sequence] ['Email'];
			$linkman [$sequence] ['remark'] = $dataArr ['linkman'] [$sequence] ['remark'];
			$tempSequence ++;
		}
		//��Ʒ�嵥
		$tempSequence = 1;
		foreach ( $dataArr ['product'] as $sequence => $equ ) {
			$product [$sequence] ['sequence'] = $tempSequence;
			$product [$sequence] ['conProductName'] = $dataArr ['product'] [$sequence] ['conProductName'];
			$product [$sequence] ['conProductDes'] = $dataArr ['product'] [$sequence] ['conProductDes'];
			$product [$sequence] ['number'] = $dataArr ['product'] [$sequence] ['number'];
			$product [$sequence] ['price'] = $dataArr ['product'] [$sequence] ['price'];
			$product [$sequence] ['money'] = $dataArr ['product'] [$sequence] ['money'];
			$tempSequence ++;
		}
		//�տ��ƻ�
		$tempSequence = 1;
		foreach ( $dataArr ['financialplan'] as $sequence => $equ ) {
			$financialplan [$sequence] ['sequence'] = $tempSequence;
			$financialplan [$sequence] ['planDate'] = $dataArr ['financialplan'] [$sequence] ['planDate'];
			$financialplan [$sequence] ['invoiceMoney'] = $dataArr ['financialplan'] [$sequence] ['invoiceMoney'];
			$financialplan [$sequence] ['incomeMoney'] = $dataArr ['financialplan'] [$sequence] ['incomeMoney'];
			$financialplan [$sequence] ['remark'] = $dataArr ['financialplan'] [$sequence] ['remark'];
			$tempSequence ++;
		}
		//�����嵥
		$tempSequence = 1;
		foreach ( $dataArr ['equ'] as $sequence => $equ ) {
			$equArr [$sequence] ['sequence'] = $tempSequence;
			$equArr [$sequence] ['productCode'] = $dataArr ['equ'] [$sequence] ['productCode'];
			$equArr [$sequence] ['productName'] = $dataArr ['equ'] [$sequence] ['productName'];
			$equArr [$sequence] ['number'] = $dataArr ['equ'] [$sequence] ['productModel'];
			$equArr [$sequence] ['warrantyPeriod'] = $dataArr ['equ'] [$sequence] ['number'];
			$tempSequence ++;
		}
		//�����嵥
		$tempSequence = 1;
		foreach ( $dataArr ['borrowequ'] as $sequence => $equ ) {
			$borrowequArr [$sequence] ['sequence'] = $tempSequence;
			$borrowequArr [$sequence] ['productCode'] = $dataArr ['borrowequ'] [$sequence] ['productCode'];
			$borrowequArr [$sequence] ['productName'] = $dataArr ['borrowequ'] [$sequence] ['productName'];
			$borrowequArr [$sequence] ['number'] = $dataArr ['borrowequ'] [$sequence] ['productModel'];
			$borrowequArr [$sequence] ['warrantyPeriod'] = $dataArr ['borrowequ'] [$sequence] ['number'];
			$tempSequence ++;
		}
//		echo '<pre>';
//		print_R($borrowequArr);
//		//��Ʊ�ƻ�
//		$tempSequence = 1;
//		foreach ( $dataArr ['invoice'] as $sequence => $equ ) {
//			$invoice [$sequence] ['sequence'] = $tempSequence;
//			$invoice [$sequence] ['money'] = $dataArr ['invoice'] [$sequence] ['money'];
//			$invoice [$sequence] ['softMoney'] = $dataArr ['invoice'] [$sequence] ['softMoney'];
//			$invoice [$sequence] ['iTypeName'] = $dataArr ['invoice'] [$sequence] ['iTypeName'];
//			$invoice [$sequence] ['invDT'] = $dataArr ['invoice'] [$sequence] ['invDT'];
//			$invoice [$sequence] ['remark'] = $dataArr ['invoice'] [$sequence] ['remark'];
//			$tempSequence ++;
//		}
//		//�տ�ƻ�
//		$tempSequence = 1;
//		foreach ( $dataArr ['income'] as $sequence => $equ ) {
//			$income [$sequence] ['receiptplan'] = $tempSequence;
//			$income [$sequence] ['money'] = $dataArr ['income'] [$sequence] ['money'];
//			$income [$sequence] ['payDT'] = $dataArr ['income'] [$sequence] ['payDT'];
//			$income [$sequence] ['pType'] = $dataArr ['income'] [$sequence] ['pType'];
//			$income [$sequence] ['collectionTerms'] = $dataArr ['income'] [$sequence] ['collectionTerms'];
//			$tempSequence ++;
//		}
//		//��ѵ�ƻ�
//		$tempSequence = 1;
//		foreach ( $dataArr ['trainListInfo'] as $sequence => $equ ) {
//			$trainingplan [$sequence] ['receiptplan'] = $tempSequence;
//			$trainingplan [$sequence] ['beginDT'] = $dataArr ['trainListInfo'] [$sequence] ['beginDT'];
//			$trainingplan [$sequence] ['endDT'] = $dataArr ['trainListInfo'] [$sequence] ['endDT'];
//			$trainingplan [$sequence] ['traNum'] = $dataArr ['trainListInfo'] [$sequence] ['traNum'];
//			$trainingplan [$sequence] ['adress'] = $dataArr ['trainListInfo'] [$sequence] ['adress'];
//			$trainingplan [$sequence] ['content'] = $dataArr ['trainListInfo'] [$sequence] ['content'];
//			$trainingplan [$sequence] ['trainer'] = $dataArr ['trainListInfo'] [$sequence] ['trainer'];
//			$tempSequence ++;
//		}
		/*************************************������Ϣ*****************************************/

		for($j = 3; $j <= 17; $j ++) { //��ȡģ���ֶβ��滻
			for($k = 'A'; $k <= 'K'; $k ++) {
				$ename = iconv ( 'utf-8', 'gb2312', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue () ); //��ȡ��Ԫ��
				if (isset ( $dataArr [$ename] )) {
					$pvalue = $dataArr [$ename];
					$objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->setValue ( iconv ( "gb2312", "utf-8", $pvalue ) );
				}
			}
		}
		/*************************************��������*****************************************/
		$i = 18;
		//���ñ�ͷ����ʽ ���� -- ��ϵ��
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i++ );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
		model_contract_common_contractExcelUtil::tableThead ( 0, $i++, '��������', $objPHPExcel );

		$objPHPExcel->getActiveSheet ()->mergeCells ( 'B' . $i . ':C' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'D' . $i . ':E' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'F' . $i . ':G' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'H' . $i . ':I' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'J' . $i . ':L' . $i );

		//���ñ�ͷ����ʽ ���� -- ��ϵ�� ���
		model_contract_common_contractExcelUtil::tableTrow ( 0, $i, '���', $objPHPExcel );
		//���ñ�ͷ����ʽ ���� -- ��ϵ�� ��ϵ������
		for($m = 1; $m < 3; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '��������', $objPHPExcel );
		}
		//���ñ�ͷ����ʽ ���� -- ��ϵ�� �绰
		for($m = 3; $m < 5; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '����ٷֱȣ�%��', $objPHPExcel );
		}
		//���ñ�ͷ����ʽ ���� -- ��ϵ��qq
		for($m = 5; $m < 7; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '�ƻ�������', $objPHPExcel );
		}
		//���ñ�ͷ����ʽ ���� -- ��ϵ�� �ʼ�
		for($m = 7; $m < 9; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '�ƻ���������', $objPHPExcel );
		}
		//���ñ�ͷ����ʽ ���� -- ��ϵ�� ��ϵ������
		for($m = 9; $m < 12; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '��ע', $objPHPExcel );
		}
		//�������
		$i++;
		if (! count ( array_filter ( $dataArr ['payment'] ) ) == 0) {
			$tempArr = array (0,1,2,2,2,2,3 );
			$row = $i;
			for($n = 0; $n < count ( $payment ); $n ++) {
				$m = 0;
				$sum = 0;
				$mergeStr1 = 'B' . $i . ':C' . $i;
				$mergeStr2 = 'D' . $i . ':E' . $i;
				$mergeStr3 = 'F' . $i . ':G' . $i;
				$mergeStr4 = 'H' . $i . ':I' . $i;
				$mergeStr5 = 'J' . $i . ':L' . $i;
				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr1 );
				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr2 );
				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr3 );
				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr4 );
				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr5 );
				foreach ( $payment [$n] as $field => $value ) {
					$sum += $tempArr [$m];
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0 + $sum, $row + $n, iconv ( "GBK", "utf-8", $value ) );
					$m ++;
					model_contract_common_contractExcelUtil::toCecter ( $i, $objPHPExcel );
				}
				$i ++;
			}
		} else {
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
			for($m = 0; $m < 12; $m ++) {
				$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '���������Ϣ' ) );
			}
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		}




		/*************************************��ͬ��ϵ����Ϣ*****************************************/
		$i ++;
		//���ñ�ͷ����ʽ ���� -- ��ϵ��
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i++ );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
		model_contract_common_contractExcelUtil::tableThead ( 0, $i++, '�ͻ���ϵ��', $objPHPExcel );

		$objPHPExcel->getActiveSheet ()->mergeCells ( 'B' . $i . ':C' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'D' . $i . ':E' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'F' . $i . ':G' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'H' . $i . ':I' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'J' . $i . ':L' . $i );

		//���ñ�ͷ����ʽ ���� -- ��ϵ�� ���
		model_contract_common_contractExcelUtil::tableTrow ( 0, $i, '���', $objPHPExcel );
		//���ñ�ͷ����ʽ ���� -- ��ϵ�� ��ϵ������
		for($m = 1; $m < 3; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '�ͻ���ϵ��', $objPHPExcel );
		}
		//���ñ�ͷ����ʽ ���� -- ��ϵ�� �绰
		for($m = 3; $m < 5; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '�绰', $objPHPExcel );
		}
		//���ñ�ͷ����ʽ ���� -- ��ϵ��qq
		for($m = 5; $m < 7; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, 'QQ', $objPHPExcel );
		}
		//���ñ�ͷ����ʽ ���� -- ��ϵ�� �ʼ�
		for($m = 7; $m < 9; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '����', $objPHPExcel );
		}
		//���ñ�ͷ����ʽ ���� -- ��ϵ�� ��ϵ������
		for($m = 9; $m < 12; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '��ע', $objPHPExcel );
		}
		//�������
		$i++;
		if (! count ( array_filter ( $dataArr ['linkman'] ) ) == 0) {
			$tempArr = array (0,1,2,2,2,2,3 );
			$row = $i;
			for($n = 0; $n < count ( $linkman ); $n ++) {
				$m = 0;
				$sum = 0;
				$mergeStr1 = 'B' . $i . ':C' . $i;
				$mergeStr2 = 'D' . $i . ':E' . $i;
				$mergeStr3 = 'F' . $i . ':G' . $i;
				$mergeStr4 = 'H' . $i . ':I' . $i;
				$mergeStr5 = 'J' . $i . ':L' . $i;
				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr1 );
				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr2 );
				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr3 );
				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr4 );
				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr5 );
				foreach ( $linkman [$n] as $field => $value ) {
					$sum += $tempArr [$m];
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0 + $sum, $row + $n, iconv ( "GBK", "utf-8", $value ) );
					$m ++;
					model_contract_common_contractExcelUtil::toCecter ( $i, $objPHPExcel );
				}
				$i ++;
			}
		} else {
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
			for($m = 0; $m < 12; $m ++) {
				$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '���������Ϣ' ) );
			}
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		}
		/*************************************��Ʒ�嵥��Ϣ*****************************************/

		//���ñ�ͷ����ʽ ���� -- ��Ʒ�嵥
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		model_contract_common_contractExcelUtil::tableThead ( 0, $i, '��Ʒ�嵥', $objPHPExcel );

		//���ñ�ͷ����ʽ ���� -- ��Ʒ�嵥 ����
		$i ++;
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'B' . $i . ':D' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'E' . $i . ':I' . $i );
		model_contract_common_contractExcelUtil::tableTrow ( 0, $i, '���', $objPHPExcel );
		for($m = 1; $m < 4; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '��Ʒ����', $objPHPExcel );
		}
		for($m = 4; $m < 9; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '��Ʒ����', $objPHPExcel );
		}
		$orderequTr = array ( '����', '����', '���' );
		for($m = 9; $m < 12; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, $orderequTr [$m-9], $objPHPExcel );
		}
		//�������
		if (! count ( array_filter ( $dataArr ['product'] ) ) == 0) {
			$i ++;
			$row = $i;
			$tempArr = array (0, 1, 3, 5, 1, 1 ,1);
			for($n = 0; $n < count ( $product ); $n ++) {
				$m = 0;
				$sum = 0;
				$objPHPExcel->getActiveSheet ()->mergeCells ( 'B' . $i . ':D' . $i );
				$objPHPExcel->getActiveSheet ()->mergeCells ( 'E' . $i . ':I' . $i );
				foreach ( $product [$n] as $field => $value ) {
					$sum+=$tempArr[$m];
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0 + $sum, $row + $n, iconv ( "gb2312", "utf-8", $value ) );
					$m++;
					model_contract_common_contractExcelUtil::toCecter ( $i, $objPHPExcel );
				}
				$i ++;
			}
		} else {
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
			for($m = 0; $m < 12; $m ++) {
				$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '���������Ϣ' ) );
			}
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		}
		
		/*************************************�տ��ƻ���Ϣ*****************************************/
		
		//���ñ�ͷ����ʽ ���� -- �տ��ƻ�
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		model_contract_common_contractExcelUtil::tableThead ( 0, $i, '�տ��ƻ�', $objPHPExcel );
		
		//���ñ�ͷ����ʽ ���� -- �տ��ƻ�����
		$i ++;
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'B' . $i . ':D' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'E' . $i . ':F' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'G' . $i . ':H' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'I' . $i . ':L' . $i );
		model_contract_common_contractExcelUtil::tableTrow ( 0, $i, '���', $objPHPExcel );
		for($m = 1; $m < 4; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '����', $objPHPExcel );
		}
		for($m = 4; $m < 6; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '��Ʊ���', $objPHPExcel );
		}
		for($m = 6; $m < 8; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '�տ���', $objPHPExcel );
		}
		for($m = 8; $m < 12; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '��ע', $objPHPExcel );
		}
		//�������
		if (! count ( array_filter ( $dataArr ['financialplan'] ) ) == 0) {
			$i ++;
			$row = $i;
			$tempArr = array (0, 1, 3, 2, 2, 4);
			for($n = 0; $n < count ( $financialplan ); $n ++) {
				$m = 0;
				$sum = 0;
				$objPHPExcel->getActiveSheet ()->mergeCells ( 'B' . $i . ':D' . $i );
				$objPHPExcel->getActiveSheet ()->mergeCells ( 'E' . $i . ':F' . $i );
				$objPHPExcel->getActiveSheet ()->mergeCells ( 'G' . $i . ':H' . $i );
				$objPHPExcel->getActiveSheet ()->mergeCells ( 'I' . $i . ':L' . $i );
				foreach ( $financialplan [$n] as $field => $value ) {
					$sum+=$tempArr[$m];
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0 + $sum, $row + $n, iconv ( "gb2312", "utf-8", $value ) );
					$m++;
					model_contract_common_contractExcelUtil::toCecter ( $i, $objPHPExcel );
				}
				$i ++;
			}
		} else {
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
			for($m = 0; $m < 12; $m ++) {
				$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '���������Ϣ' ) );
			}
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		}

		/*************************************������ת���������嵥��Ϣ*****************************************/

		//���ñ�ͷ����ʽ ���� -- ��Ʒ�嵥
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		model_contract_common_contractExcelUtil::tableThead ( 0, $i, '����ת��������', $objPHPExcel );

		//���ñ�ͷ����ʽ ���� -- ��Ʒ�嵥 ����
		$i ++;
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'B' . $i . ':E' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'F' . $i . ':J' . $i );
		model_contract_common_contractExcelUtil::tableTrow ( 0, $i, '���', $objPHPExcel );
		for($m = 1; $m < 5; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '���ϱ��', $objPHPExcel );
		}
		for($m = 5; $m < 11; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '��������', $objPHPExcel );
		}
		$orderequTr = array (  '�ͺ�', '����' );
		for($m = 10; $m < 12; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, $orderequTr [$m-10], $objPHPExcel );
		}
		//�������
		if (! count ( array_filter ( $dataArr ['borrowequ'] ) ) == 0) {
			$i ++;
			$row = $i;
			$tempArr = array (0, 1, 4, 5 ,1);
			for($n = 0; $n < count ( $borrowequArr ); $n ++) {
				$m = 0;
				$sum = 0;
				$objPHPExcel->getActiveSheet ()->mergeCells ( 'B' . $i . ':E' . $i );
				$objPHPExcel->getActiveSheet ()->mergeCells ( 'F' . $i . ':J' . $i );
				foreach ( $borrowequArr [$n] as $field => $value ) {
					$sum+=$tempArr[$m];
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0 + $sum, $row + $n, iconv ( "gb2312", "utf-8", $value ) );
					$m++;
					model_contract_common_contractExcelUtil::toCecter ( $i, $objPHPExcel );
				}
				$i ++;
			}
		} else {
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
			for($m = 0; $m < 12; $m ++) {
				$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '���������Ϣ' ) );
			}
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		}

		/*************************************�����嵥��Ϣ*****************************************/

		//���ñ�ͷ����ʽ ���� -- ��Ʒ�嵥
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		model_contract_common_contractExcelUtil::tableThead ( 0, $i, '��ͬ�����嵥', $objPHPExcel );

		//���ñ�ͷ����ʽ ���� -- ��Ʒ�嵥 ����
		$i ++;
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'B' . $i . ':E' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'F' . $i . ':J' . $i );
		model_contract_common_contractExcelUtil::tableTrow ( 0, $i, '���', $objPHPExcel );
		for($m = 1; $m < 5; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '���ϱ��', $objPHPExcel );
		}
		for($m = 5; $m < 11; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '��������', $objPHPExcel );
		}
		$orderequTr = array (  '�ͺ�', '����' );
		for($m = 10; $m < 12; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, $orderequTr [$m-10], $objPHPExcel );
		}
		//�������
		if (! count ( array_filter ( $dataArr ['equ'] ) ) == 0) {
			$i ++;
			$row = $i;
			$tempArr = array (0, 1, 4, 5 ,1);
			for($n = 0; $n < count ( $equArr ); $n ++) {
				$m = 0;
				$sum = 0;
				$objPHPExcel->getActiveSheet ()->mergeCells ( 'B' . $i . ':E' . $i );
				$objPHPExcel->getActiveSheet ()->mergeCells ( 'F' . $i . ':J' . $i );
				foreach ( $equArr [$n] as $field => $value ) {
					$sum+=$tempArr[$m];
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0 + $sum, $row + $n, iconv ( "gb2312", "utf-8", $value ) );
					$m++;
					model_contract_common_contractExcelUtil::toCecter ( $i, $objPHPExcel );
				}
				$i ++;
			}
		} else {
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
			for($m = 0; $m < 12; $m ++) {
				$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '���������Ϣ' ) );
			}
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		}
//		/*************************************��Ʊ�ƻ���Ϣ*****************************************/
//		//���ñ�ͷ����ʽ ���� -- ��Ʊ�ƻ�
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++$i . ':L' . $i );
//		model_contract_common_contractExcelUtil::tableThead ( 0, $i, '��Ʊ�ƻ�', $objPHPExcel );
//		$i ++;
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'B' . $i . ':C' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'D' . $i . ':E' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'F' . $i . ':G' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'H' . $i . ':I' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'J' . $i . ':L' . $i );
//		//���ñ�ͷ����ʽ ���� -- ��Ʊ�ƻ� ����
//		model_contract_common_contractExcelUtil::tableTrow ( 0, $i, '���', $objPHPExcel );
//		for($m = 1; $m < 3; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '��Ʊ���', $objPHPExcel );
//		}
//		for($m = 3; $m < 5; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '����������', $objPHPExcel );
//		}
//		for($m = 5; $m < 7; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '��Ʊ����', $objPHPExcel );
//		}
//		for($m = 7; $m < 9; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '��Ʊ����', $objPHPExcel );
//		}
//		for($m = 9; $m < 12; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '��Ʊ����', $objPHPExcel );
//		}
//		//�������
//		if (! count ( array_filter ( $dataArr ['invoice'] ) ) == 0) {
//			$i ++;
//			$tempArr = array (0, 1, 2, 2, 2, 2 );
//			$row = $i;
//			for($n = 0; $n < count ( $invoice ); $n ++) {
//				$m = 0;
//				$sum = 0;
//				$mergeStr1 = 'B' . $i . ':C' . $i;
//				$mergeStr2 = 'D' . $i . ':E' . $i;
//				$mergeStr3 = 'F' . $i . ':G' . $i;
//				$mergeStr4 = 'H' . $i . ':I' . $i;
//				$mergeStr5 = 'J' . $i . ':L' . $i;
//				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr1 );
//				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr2 );
//				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr3 );
//				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr4 );
//				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr5 );
//				foreach ( $invoice [$n] as $field => $value ) {
//					$sum += $tempArr [$m];
//					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0 + $sum, $row + $n, iconv ( "GBK", "utf-8", $value ) );
//					$m ++;
//					model_contract_common_contractExcelUtil::toCecter ( $i, $objPHPExcel );
//				}
//				$i ++;
//			}
//		} else {
//			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
//			for($m = 0; $m < 12; $m ++) {
//				$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
//				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '���������Ϣ' ) );
//			}
//			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
//		}
//		/*************************************�տ�ƻ���Ϣ*****************************************/
//		//���ñ�ͷ����ʽ ���� -- �տ�ƻ� ����
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
//		model_contract_common_contractExcelUtil::tableThead ( 0, $i, '�տ�ƻ�', $objPHPExcel );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++$i . ':B' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'C' . $i . ':L' . $i );
//		for($m = 0; $m < 2; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '֧������:', $objPHPExcel );
//
//		}
//		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $i, iconv ( "GBK", "utf-8", $dataArr ['paymentterm'] ) );
//
//		$i ++;
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'B' . $i . ':C' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'D' . $i . ':E' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'F' . $i . ':G' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'H' . $i . ':L' . $i );
//		model_contract_common_contractExcelUtil::tableTrow ( 0, $i, '���', $objPHPExcel );
//		for($m = 1; $m < 3; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '�տ���', $objPHPExcel );
//		}
//		for($m = 3; $m < 5; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '�տ�����', $objPHPExcel );
//		}
//		for($m = 5; $m < 7; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '�տʽ', $objPHPExcel );
//		}
//		for($m = 7; $m < 12; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '�տ�����', $objPHPExcel );
//		}
//		//�������
//		if (! count ( array_filter ( $dataArr ['income'] ) ) == 0) {
//			$i ++;
//			$tempArr = array (0, 1, 2, 2, 2 );
//			$row = $i;
//			for($n = 0; $n < count ( $income ); $n ++) {
//				$m = 0;
//				$sum = 0;
//				$mergeStr1 = 'B' . $i . ':C' . $i;
//				$mergeStr2 = 'D' . $i . ':E' . $i;
//				$mergeStr3 = 'F' . $i . ':G' . $i;
//				$mergeStr4 = 'H' . $i . ':L' . $i;
//				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr1 );
//				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr2 );
//				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr3 );
//				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr4 );
//				foreach ( $income [$n] as $field => $value ) {
//					$sum += $tempArr [$m];
//					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0 + $sum, $row + $n, iconv ( "GBK", "utf-8", $value ) );
//					$m ++;
//					model_contract_common_contractExcelUtil::toCecter ( $i, $objPHPExcel );
//				}
//				$i ++;
//			}
//		} else {
//			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
//			for($m = 0; $m < 12; $m ++) {
//				$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
//				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '���������Ϣ' ) );
//			}
//			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
//		}
		/*************************************��ѵ�ƻ���Ϣ*****************************************/

//		//���ñ�ͷ����ʽ ���� -- ��ѵ�ƻ��嵥
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
//		model_contract_common_contractExcelUtil::tableThead ( 0, $i, '��ѵ�ƻ�', $objPHPExcel );
//
//		//���ñ�ͷ����ʽ ���� --��ѵ�ƻ��嵥 ����
//		$orderequTr = array ('���', '��ѵ��ʼʱ��', '��ѵ����ʱ��', '��������' );
//		$i ++;
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'E' . $i . ':F' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'G' . $i . ':I' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'J' . $i . ':L' . $i );
//		for($m = 0; $m < 4; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, $orderequTr [$m], $objPHPExcel );
//		}
//		for($m = 4; $m < 6; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '��ѵ�ص�', $objPHPExcel );
//		}
//		for($m = 6; $m < 9; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '��ѵ����', $objPHPExcel );
//		}
//		for($m = 9; $m < 12; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '��ѵ����ʦҪ��', $objPHPExcel );
//		}
//		//�������
//		if (! count ( array_filter ( $dataArr ['trainListInfo'] ) ) == 0) {
//			$i ++;
//			$row = $i;
//			$tempArr = array (0, 1, 1, 1, 1, 2, 3 );
//			for($n = 0; $n < count ( $trainingplan ); $n ++) {
//				$m = 0;
//				$sum = 0;
//				$objPHPExcel->getActiveSheet ()->mergeCells ( 'E' . $i . ':F' . $i );
//				$objPHPExcel->getActiveSheet ()->mergeCells ( 'G' . $i . ':I' . $i );
//				$objPHPExcel->getActiveSheet ()->mergeCells ( 'J' . $i . ':L' . $i );
//				foreach ( $trainingplan [$n] as $field => $value ) {
//					$sum += $tempArr [$m];
//					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0 + $sum, $row + $n, iconv ( "GBK", "utf-8", $value ) );
//					$m ++;
//					model_contract_common_contractExcelUtil::toCecter ( $i, $objPHPExcel );
//				}
//				$i ++;
//			}
//		} else {
//			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
//			for($m = 0; $m < 12; $m ++) {
//				$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
//				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '���������Ϣ' ) );
//			}
//			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
//		}
//		/*************************************����������Ϣ*****************************************/
//		//���ñ�ͷ����ʽ ���� -- ���������嵥
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
//		for($m = 0; $m < 12; $m ++) {
//			model_contract_common_contractExcelUtil::tableThead ( $m, $i, '��������', $objPHPExcel );
//			$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getBorders ()->getBottom ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
//		}
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . ++ $i );
//		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( 0, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
//		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $i - 1, iconv ( "GBK", "utf-8", $dataArr ['warrantyClause'] ) );
//
//		/*************************************�ۺ�Ҫ����Ϣ*****************************************/
//		//���ñ�ͷ����ʽ ���� -- �ۺ�Ҫ���嵥
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
//		for($m = 0; $m < 12; $m ++) {
//			model_contract_common_contractExcelUtil::tableThead ( $m, $i, '�ۺ�Ҫ��', $objPHPExcel );
//			$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getBorders ()->getBottom ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
//		}
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . ++ $i );
//		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( 0, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
//		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $i - 1, iconv ( "GBK", "utf-8", $dataArr ['afterService'] ) );

		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
//		//�������
		ob_end_clean (); //��������������������������
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "��" . $dataArr ['contractCode'] . "����ͬ.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}

    /*
     * $cellValueΪ����Ķ�ά����
     */
	public static function export2ExcelUtil($thArr, $rowdatas) {
		//		$stime=microtime(true); //��ȡ����ʼִ�е�ʱ��
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
		//		//����һ��Excel������
		//		$objPhpExcelFile = new PHPExcel();

		$objReader = PHPExcel_IOFactory :: createReader('Excel2007'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/orderReportList07.xlsx"); //��ȡģ��
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel2007($objPhpExcelFile);

		//�����ǶԹ������������������
		//���õ�ǰ�����������
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", '��Ϣ�б�'));
		//���ñ�ͷ����ʽ ����
		//		$highestColumn = chr(ord('A') + count($thArr) - 1);
		//		$objPhpExcelFile->getActiveSheet()->mergeCells('A1:G1');
		//		for ($m = 0; $m < count($thArr); $m++) {
		//			model_contract_common_contractExcelUtil :: tableThead(0, 1, '�б���Ϣ', $objPhpExcelFile);
		//			model_contract_common_contractExcelUtil :: toCecter(1, $objPhpExcelFile);
		//		}
		//���ñ�ͷ����ʽ ����
		$theadColumn = 0;
		foreach ($thArr as $key => $val) {
			if ($key == 'orderCode' || $key == 'orderTempCode' || $key == 'orderName') {
				model_contract_common_contractExcelUtil :: tableTrowLong($theadColumn, 1, $val, $objPhpExcelFile);
			} else
				model_contract_common_contractExcelUtil :: tableTrow($theadColumn, 1, $val, $objPhpExcelFile);
			$theadColumn++;
		}
		$i = 2;

		if (!count(array_filter($rowdatas)) == 0) {
			$row = $i;
			for ($n = 0; $n < count($rowdatas); $n++) {
				$m = 0;
				foreach ($rowdatas[$n] as $field => $value) {
					$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $row + $n, iconv("gb2312", "utf-8", $value));
					if ($field == "surincomeMoney" || $field == "surOrderMoney" || $field == "surOrderMoney") {
						$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $n, iconv("GBK", "utf-8", $value), PHPExcel_Cell_DataType :: TYPE_NUMERIC);
					}

					$m++;
					//model_contract_common_contractExcelUtil :: toCecter($i, $objPhpExcelFile);
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
		ob_end_clean(); //��������������������������
		header("Content-type: text/html; charset=UTF-8");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "�б���Ϣ.xlsx" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
		//$etime=microtime(true);//��ȡ����ִ�н�����ʱ��
		//$total=$etime-$stime;   //�����ֵ
		//echo "<br />{$total} times";
	}


	/*
	 * �����ܱ�������Ϣ
	 */
	public static function excelTemp($thArr, $rowdatas) {ob_start();
//		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
//		$objPHPExcel = $objReader->load ( "upfile/orderReportList07(new).xlsx" ); //��ȡģ��
//		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );


		$objReader = PHPExcel_IOFactory :: createReader('Excel2007'); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load("upfile/orderReportList07.xlsx"); //��ȡģ��
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

//		$sheet = $objPHPExcel->getSheet ( 0 );
//		$highestRow = $sheet->getHighestRow (); // ȡ��������
//		$highestColumn = $sheet->getHighestColumn (); // ȡ��������
//
//		$theadColumn = 0;
//		foreach ($thArr as $key => $val) {
//			if ($key == 'orderCode' || $key == 'orderTempCode' || $key == 'orderName') {
//				model_contract_common_contractExcelUtil :: tableTrowLong($theadColumn, 1, $val, $objPHPExcel);
//			} else
//				model_contract_common_contractExcelUtil :: tableTrow($theadColumn, 1, $val, $objPHPExcel);
//			$theadColumn++;
//		}
//		$i = 2;
//
//		if (!count(array_filter($rowdatas)) == 0) {
//			$row = $i;
//			for ($n = 0; $n < count($rowdatas); $n++) {
//				$m = 0;
//				foreach ($rowdatas[$n] as $field => $value) {
//					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($m, $row + $n, iconv("gb2312", "utf-8", $value));
//					if ($field == "surincomeMoney" || $field == "surOrderMoney" || $field == "surOrderMoney") {
//						$objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $n, iconv("GBK", "utf-8", $value), PHPExcel_Cell_DataType :: TYPE_NUMERIC);
//					}
//
//					$m++;
//					//model_contract_common_contractExcelUtil :: toCecter($i, $objPhpExcelFile);
//				}
//				$i++;
//			}
//		} else {
//			$objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':' . $highestColumn . $i);
//			for ($m = 0; $m < count($thArr); $m++) {
//				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($m, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
//				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($m, $i, iconv("gb2312", "utf-8", '���������Ϣ'));
//			}
//		}

		//�������
		ob_end_clean(); //��������������������������
header("Content-type: text/html; charset=UTF-8");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "�б���Ϣ.xlsx" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}
	
	/**
	 * excel���ɲ���
	 * @param $thArr
	 * @param $data
	 * @param string $fileName
	 * @throws Exception
	 */
	public static function exportBasicExcelUtil($thArr, $data, $fileName = '�б���Ϣ') {
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
	
		$objReader = PHPExcel_IOFactory:: createReader('Excel5'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/orderReportList07.xls"); //��ȡģ��
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);
		//�����Զ��и�
		$objPhpExcelFile->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
		//���ñ�ͷ����ʽ ����
		$headColumn = 0;
		foreach ($thArr as $v) {
			self::tableTrow($headColumn, 1, $v, $objPhpExcelFile);
			$headColumn++;
		}
	
		$i = 2;
		if (!empty($data)) {
			$row = $i;
			foreach ($data as $key => $val) {
				$m = 0;
				foreach ($thArr as $field => $value) {
					//�Զ�����
					$objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $row + $key)->getAlignment()->setWrapText(true);
					//�����ʾΪ��ѧ������������
					$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key,
							iconv("gb2312", "utf-8", $val[$field]), PHPExcel_Cell_DataType::TYPE_STRING);
					$m++;
				}
			}
		}
		//�������
		ob_end_clean(); //��������������������������
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition:inline;filename=" . $fileName . ".xls");
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}
}
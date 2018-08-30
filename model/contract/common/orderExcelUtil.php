<?php
/**
 * @description: ʵ�����ݵ�����Excel�Ĺ���
 * @date 2010-10-20 ����07:18:35
 */

include "module/phpExcel/classes/PHPExcel.php";
include "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";

class model_contract_common_orderExcelUtil {

	//ģ������ʽ
	function tableThead($column, $row, $content, $objPHPExcel) {
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFont ()->setSize ( 12 );
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFont ()->setBold ( true );
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFill ()->getStartColor ()->setARGB ( "00D2DFD6" );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $column, $row, iconv ( "gb2312", "utf-8", $content ) );
	}
	//ģ������ʽ
	function tableTrow($column, $row, $content, $objPHPExcel) {
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFont ()->setSize ( 10 );
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getBorders ()->getBottom ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getBorders ()->getTop ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getBorders ()->getLeft ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getBorders ()->getRight ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFill ()->getStartColor ()->setARGB ( "00ECF8FB" );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $column, $row, iconv ( "gb2312", "utf-8", $content ) );
	}
	//ģ�����ݾ�����ʽ
	function toCecter($i, $objPHPExcel) {
		for($temp = 0; $temp < 12; $temp ++) {
			$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $temp, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		}
	}

	//��ȡģ�岢����
	public static function exporTemplate($dataArr, $rowdatas, $imageName) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/contExport.xls" ); //��ȡģ��
		$excelActiveSheet = $objPHPExcel->getActiveSheet ();
		//���ñ�����ļ����Ƽ�·��
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;
		$objPhpExcelFile = new PHPExcel ();

		//���ñ�ͷ����ʽ ����
		for($m = 0; $m < 12; $m ++) {
			model_contract_common_orderExcelUtil::tableThead ( $m, 1, '��ͬ��Ϣ', $objPHPExcel );
		}
		foreach ( $dataArr as $key => $val ) {
			if ($val === null) {
				$dataArr [$key] [$val] = '';
			}
		}
		//��ȡ�ӱ�����
		//��ͬ��ϵ��
		$tempSequence = 1;
		foreach ( $dataArr ['linkman'] as $sequence => $equ ) {
			$linkman [$sequence] ['sequence'] = $tempSequence;
			$linkman [$sequence] ['linkman'] = $dataArr ['linkman'] [$sequence] ['linkman'];
			$linkman [$sequence] ['telephone'] = $dataArr ['linkman'] [$sequence] ['telephone'];
			$linkman [$sequence] ['email'] = $dataArr ['linkman'] [$sequence] ['email'];
			$linkman [$sequence] ['remark'] = $dataArr ['linkman'] [$sequence] ['remark'];
			$tempSequence ++;
		}
		//��Ʒ�嵥
		$tempSequence = 1;
		foreach ( $dataArr ['orderequ'] as $sequence => $equ ) {
			$orderequ [$sequence] ['sequence'] = $tempSequence;
//			$orderequ [$sequence] ['productLine'] = $dataArr ['orderequ'] [$sequence] ['productLine'];
			$orderequ [$sequence] ['productNo'] = $dataArr ['orderequ'] [$sequence] ['productNo'];
			$orderequ [$sequence] ['productName'] = $dataArr ['orderequ'] [$sequence] ['productName'];
			$orderequ [$sequence] ['productModel'] = $dataArr ['orderequ'] [$sequence] ['productModel'];
			$orderequ [$sequence] ['number'] = $dataArr ['orderequ'] [$sequence] ['number'];
			$orderequ [$sequence] ['price'] = $dataArr ['orderequ'] [$sequence] ['price'];
			$orderequ [$sequence] ['money'] = $dataArr ['orderequ'] [$sequence] ['money'];
			$orderequ [$sequence] ['projArraDate'] = $dataArr ['orderequ'] [$sequence] ['projArraDate'];
			$orderequ [$sequence] ['warrantyPeriod'] = $dataArr ['orderequ'] [$sequence] ['warrantyPeriod'];
			$orderequ [$sequence] ['license'] = '';
			if ($dataArr ['orderequ'] [$sequence] ['isSell'] == null) {
				$orderequ [$sequence] ['isSell'] = '��';
			} else {
				$orderequ [$sequence] ['isSell'] = '��';
			}
			$tempSequence ++;
		}
		echo "<pre>";
		print_R($orderequ);
		//�Զ����Ʒ�嵥
		$tempSequence = 1;
		foreach ( $dataArr ['customizelist'] as $sequence => $equ ) {
			$customizelist [$sequence] ['sequence'] = $tempSequence;
//			$customizelist [$sequence] ['productLine'] = $dataArr ['customizelist'] [$sequence] ['productLine'];
			$customizelist [$sequence] ['productCode'] = $dataArr ['customizelist'] [$sequence] ['productCode'];
			$customizelist [$sequence] ['productName'] = $dataArr ['customizelist'] [$sequence] ['productName'];
			$customizelist [$sequence] ['productModel'] = $dataArr ['customizelist'] [$sequence] ['productModel'];
			$customizelist [$sequence] ['number'] = $dataArr ['customizelist'] [$sequence] ['number'];
			$customizelist [$sequence] ['price'] = $dataArr ['customizelist'] [$sequence] ['price'];
			$customizelist [$sequence] ['money'] = $dataArr ['customizelist'] [$sequence] ['money'];
			$customizelist [$sequence] ['projArraDT'] = $dataArr ['customizelist'] [$sequence] ['projArraDT'];
			$customizelist [$sequence] ['remark'] = $dataArr ['customizelist'] [$sequence] ['remark'];
			if ($dataArr ['customizelist'] [$sequence] ['isSell'] == null) {
				$customizelist [$sequence] ['isSell'] = '��';
			} else {
				$customizelist [$sequence] ['isSell'] = '��';
			}
			$tempSequence ++;
		}
		//��Ʊ�ƻ�
		$tempSequence = 1;
		foreach ( $dataArr ['invoice'] as $sequence => $equ ) {
			$invoice [$sequence] ['sequence'] = $tempSequence;
			$invoice [$sequence] ['money'] = $dataArr ['invoice'] [$sequence] ['money'];
			$invoice [$sequence] ['softM'] = $dataArr ['invoice'] [$sequence] ['softM'];
			$invoice [$sequence] ['iTypeName'] = $dataArr ['invoice'] [$sequence] ['iTypeName'];
			$invoice [$sequence] ['invDT'] = $dataArr ['invoice'] [$sequence] ['invDT'];
			$invoice [$sequence] ['remark'] = $dataArr ['invoice'] [$sequence] ['remark'];
			$tempSequence ++;
		}
		//�տ�ƻ�
		$tempSequence = 1;
		foreach ( $dataArr ['receiptplan'] as $sequence => $equ ) {
			$receiptplan [$sequence] ['receiptplan'] = $tempSequence;
			$receiptplan [$sequence] ['money'] = $dataArr ['receiptplan'] [$sequence] ['money'];
			$receiptplan [$sequence] ['payDT'] = $dataArr ['receiptplan'] [$sequence] ['payDT'];
			$receiptplan [$sequence] ['pType'] = $dataArr ['receiptplan'] [$sequence] ['pType'];
			$receiptplan [$sequence] ['collectionTerms'] = $dataArr ['receiptplan'] [$sequence] ['collectionTerms'];
			$tempSequence ++;
		}
		//��ѵ�ƻ�
		$tempSequence = 1;
		foreach ( $dataArr ['trainingplan'] as $sequence => $equ ) {
			$trainingplan [$sequence] ['receiptplan'] = $tempSequence;
			$trainingplan [$sequence] ['beginDT'] = $dataArr ['trainingplan'] [$sequence] ['beginDT'];
			$trainingplan [$sequence] ['endDT'] = $dataArr ['trainingplan'] [$sequence] ['endDT'];
			$trainingplan [$sequence] ['traNum'] = $dataArr ['trainingplan'] [$sequence] ['traNum'];
			$trainingplan [$sequence] ['adress'] = $dataArr ['trainingplan'] [$sequence] ['adress'];
			$trainingplan [$sequence] ['content'] = $dataArr ['trainingplan'] [$sequence] ['content'];
			$trainingplan [$sequence] ['trainer'] = $dataArr ['trainingplan'] [$sequence] ['trainer'];
			$tempSequence ++;
		}
		/*************************************������Ϣ*****************************************/

		for($j = 3; $j <= 10; $j ++) { //��ȡģ���ֶβ��滻
			for($k = 'A'; $k <= 'K'; $k ++) {
				$ename = iconv ( 'utf-8', 'gb2312', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue () ); //��ȡ��Ԫ��
				if (isset ( $dataArr [$ename] )) {
					$pvalue = $dataArr [$ename];
					$objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->setValue ( iconv ( "gb2312", "utf-8", $pvalue ) );
				}
			}
		}
		/*************************************��ͬ��ϵ����Ϣ*****************************************/
		$i = 11;
		//���ñ�ͷ����ʽ ���� -- ��ϵ��
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A11:L11' );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A12:L12' );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'B13:C13' );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'D13:F13' );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'G13:I13' );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'J13:L13' );
		for($m = 0; $m < 12; $m ++) {
			$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, 10 )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
			$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, 10 )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			model_contract_common_orderExcelUtil::tableThead ( $m, 11, '�ͻ���ϵ��', $objPHPExcel );
		}
		//���ñ�ͷ����ʽ ���� -- ��ϵ�� ���
		model_contract_common_orderExcelUtil::tableTrow ( 0, 12, '���', $objPHPExcel );
		//���ñ�ͷ����ʽ ���� -- ��ϵ�� ��ϵ������
		for($m = 1; $m < 3; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, 12, '�ͻ���ϵ��', $objPHPExcel );
		}
		//���ñ�ͷ����ʽ ���� -- ��ϵ�� �绰
		for($m = 3; $m < 6; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, 12, '�绰', $objPHPExcel );
		}
		//���ñ�ͷ����ʽ ���� -- ��ϵ�� �ʼ�
		for($m = 6; $m < 9; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, 12, '�ʼ�', $objPHPExcel );
		}
		//���ñ�ͷ����ʽ ���� -- ��ϵ�� ��ϵ������
		for($m = 9; $m < 12; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, 12, '��ע', $objPHPExcel );
		}
		//�������
		$i = 13;
		if (! count ( array_filter ( $dataArr ['linkman'] ) ) == 0) {
			$tempArr = array (0, 1, 2, 3, 3 );
			for($n = 0; $n < count ( $linkman ); $n ++) {
				$m = 0;
				$sum = 0;
				$mergeStr1 = 'B' . $i . ':C' . $i;
				$mergeStr2 = 'D' . $i . ':F' . $i;
				$mergeStr3 = 'G' . $i . ':I' . $i;
				$mergeStr4 = 'J' . $i . ':L' . $i;
				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr1 );
				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr2 );
				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr3 );
				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr4 );
				foreach ( $linkman [$n] as $field => $value ) {
					$sum += $tempArr [$m];
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0 + $sum, 13 + $n, iconv ( "GBK", "utf-8", $value ) );
					$m ++;
					model_contract_common_orderExcelUtil::toCecter ( $i, $objPHPExcel );
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
		model_contract_common_orderExcelUtil::tableThead ( 0, $i, '��Ʒ�嵥', $objPHPExcel );

		//���ñ�ͷ����ʽ ���� -- ��Ʒ�嵥 ����
		$i ++;
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'C' . $i . ':D' . $i );
		model_contract_common_orderExcelUtil::tableTrow ( 0, $i, '���', $objPHPExcel );
		model_contract_common_orderExcelUtil::tableTrow ( 1, $i, '��Ʒ���', $objPHPExcel );
		for($m = 2; $m < 4; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '��Ʒ����', $objPHPExcel );
		}
		$orderequTr = array ( '��Ʒ�ͺ�', '����', '����', '���', '�ƻ���������', '������', '��������', '��ͬ��' );
		for($m = 4; $m < 12; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, $orderequTr [$m-4], $objPHPExcel );
		}
		//�������
		if (! count ( array_filter ( $dataArr ['orderequ'] ) ) == 0) {
			$i ++;
			$row = $i;
			$tempArr = array (0, 1, 1, 2, 1, 1, 1, 1, 1, 1, 1 );
			for($n = 0; $n < count ( $orderequ ); $n ++) {
				$m = 0;
				$sum = 0;
				$objPHPExcel->getActiveSheet ()->mergeCells ( 'C' . $i . ':D' . $i );
				foreach ( $orderequ [$n] as $field => $value ) {
					$sum+=$tempArr[$m];
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0 + $sum, $row + $n, iconv ( "gb2312", "utf-8", $value ) );
					$m++;
					model_contract_common_orderExcelUtil::toCecter ( $i, $objPHPExcel );
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
		/*************************************�Զ����Ʒ�嵥��Ϣ*****************************************/

		//���ñ�ͷ����ʽ ���� -- �Զ����Ʒ�嵥
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		model_contract_common_orderExcelUtil::tableThead ( 0, $i, '�Զ����Ʒ�嵥', $objPHPExcel );
		$i ++;
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'C' . $i . ':D' . $i );
		model_contract_common_orderExcelUtil::tableTrow ( 0, $i, '���', $objPHPExcel );
		model_contract_common_orderExcelUtil::tableTrow ( 1, $i, '��Ʒ���', $objPHPExcel );
		for($m = 2; $m < 4; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '��Ʒ����', $objPHPExcel );
		}
		//���ñ�ͷ����ʽ ���� -- �Զ����Ʒ�嵥 ����
		$orderequTr = array ( '��Ʒ�ͺ�', '����', '����', '���', '�ƻ���������' );
		for($m = 4; $m < 9; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, $orderequTr [$m-4], $objPHPExcel );
		}
		//���ñ�ͷ����ʽ ���� -- �Զ����Ʒ�嵥 ����
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'J' . $i . ':K' . $i );
		for($m = 9; $m < 11; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '��ע', $objPHPExcel );
		}
		model_contract_common_orderExcelUtil::tableTrow ( 11, $i, '��ͬ��', $objPHPExcel );

		//�������
		if (! count ( array_filter ( $dataArr ['customizelist'] ) ) == 0) {
			$i ++;
			$row = $i;
			$tempArr = array (0, 1, 1, 2, 1, 1, 1, 1, 1, 2, 1 );
			for($n = 0; $n < count ( $customizelist ); $n ++) {
				$m = 0;
				$sum = 0;
				$objPHPExcel->getActiveSheet ()->mergeCells ( 'C' . $i . ':D' . $i );
				$objPHPExcel->getActiveSheet ()->mergeCells ( 'J' . $i . ':K' . $i );
				foreach ( $customizelist [$n] as $field => $value ) {
					$sum += $tempArr [$m];
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0 + $sum, $row + $n, iconv ( "GBK", "utf-8", $value ) );
					$m ++;
					model_contract_common_orderExcelUtil::toCecter ( $i, $objPHPExcel );
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
		/*************************************��Ʊ�ƻ���Ϣ*****************************************/
		//���ñ�ͷ����ʽ ���� -- ��Ʊ�ƻ�
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		model_contract_common_orderExcelUtil::tableThead ( 0, $i, '��Ʊ�ƻ�', $objPHPExcel );
		$i ++;
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'B' . $i . ':C' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'D' . $i . ':E' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'F' . $i . ':G' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'H' . $i . ':I' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'J' . $i . ':L' . $i );
		//���ñ�ͷ����ʽ ���� -- ��Ʊ�ƻ� ����
		model_contract_common_orderExcelUtil::tableTrow ( 0, $i, '���', $objPHPExcel );
		for($m = 1; $m < 3; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '��Ʊ���', $objPHPExcel );
		}
		for($m = 3; $m < 5; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '����������', $objPHPExcel );
		}
		for($m = 5; $m < 7; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '��Ʊ����', $objPHPExcel );
		}
		for($m = 7; $m < 9; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '��Ʊ����', $objPHPExcel );
		}
		for($m = 9; $m < 12; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '��Ʊ����', $objPHPExcel );
		}
		//�������
		if (! count ( array_filter ( $dataArr ['invoice'] ) ) == 0) {
			$i ++;
			$tempArr = array (0, 1, 2, 2, 2, 2 );
			$row = $i;
			for($n = 0; $n < count ( $invoice ); $n ++) {
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
				foreach ( $invoice [$n] as $field => $value ) {
					$sum += $tempArr [$m];
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0 + $sum, $row + $n, iconv ( "GBK", "utf-8", $value ) );
					$m ++;
					model_contract_common_orderExcelUtil::toCecter ( $i, $objPHPExcel );
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
		/*************************************�տ�ƻ���Ϣ*****************************************/
		//���ñ�ͷ����ʽ ���� -- �տ�ƻ� ����
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		model_contract_common_orderExcelUtil::tableThead ( 0, $i, '�տ�ƻ�', $objPHPExcel );
		$i ++;
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'B' . $i . ':C' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'D' . $i . ':E' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'F' . $i . ':G' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'H' . $i . ':L' . $i );
		model_contract_common_orderExcelUtil::tableTrow ( 0, $i, '���', $objPHPExcel );
		for($m = 1; $m < 3; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '�տ���', $objPHPExcel );
		}
		for($m = 3; $m < 5; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '�տ�����', $objPHPExcel );
		}
		for($m = 5; $m < 7; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '�տʽ', $objPHPExcel );
		}
		for($m = 7; $m < 12; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '�տ�����', $objPHPExcel );
		}
		//�������
		if (! count ( array_filter ( $dataArr ['receiptplan'] ) ) == 0) {
			$i ++;
			$tempArr = array (0, 1, 2, 2, 2 );
			$row = $i;
			for($n = 0; $n < count ( $receiptplan ); $n ++) {
				$m = 0;
				$sum = 0;
				$mergeStr1 = 'B' . $i . ':C' . $i;
				$mergeStr2 = 'D' . $i . ':E' . $i;
				$mergeStr3 = 'F' . $i . ':G' . $i;
				$mergeStr4 = 'H' . $i . ':L' . $i;
				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr1 );
				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr2 );
				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr3 );
				$objPHPExcel->getActiveSheet ()->mergeCells ( $mergeStr4 );
				foreach ( $receiptplan [$n] as $field => $value ) {
					$sum += $tempArr [$m];
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0 + $sum, $row + $n, iconv ( "GBK", "utf-8", $value ) );
					$m ++;
					model_contract_common_orderExcelUtil::toCecter ( $i, $objPHPExcel );
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
		/*************************************��ѵ�ƻ���Ϣ*****************************************/

		//���ñ�ͷ����ʽ ���� -- ��ѵ�ƻ��嵥
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		model_contract_common_orderExcelUtil::tableThead ( 0, $i, '��ѵ�ƻ�', $objPHPExcel );

		//���ñ�ͷ����ʽ ���� --��ѵ�ƻ��嵥 ����
		$orderequTr = array ('���', '��ѵ��ʼʱ��', '��ѵ����ʱ��', '��������' );
		$i ++;
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'E' . $i . ':F' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'G' . $i . ':I' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'J' . $i . ':L' . $i );
		for($m = 0; $m < 4; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, $orderequTr [$m], $objPHPExcel );
		}
		for($m = 4; $m < 6; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '��ѵ�ص�', $objPHPExcel );
		}
		for($m = 6; $m < 9; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '��ѵ����', $objPHPExcel );
		}
		for($m = 9; $m < 12; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '��ѵ����ʦҪ��', $objPHPExcel );
		}
		//�������
		if (! count ( array_filter ( $dataArr ['trainingplan'] ) ) == 0) {
			$i ++;
			$row = $i;
			$tempArr = array (0, 1, 1, 1, 1, 2, 3 );
			for($n = 0; $n < count ( $trainingplan ); $n ++) {
				$m = 0;
				$sum = 0;
				$objPHPExcel->getActiveSheet ()->mergeCells ( 'E' . $i . ':F' . $i );
				$objPHPExcel->getActiveSheet ()->mergeCells ( 'G' . $i . ':I' . $i );
				$objPHPExcel->getActiveSheet ()->mergeCells ( 'J' . $i . ':L' . $i );
				foreach ( $trainingplan [$n] as $field => $value ) {
					$sum += $tempArr [$m];
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0 + $sum, $row + $n, iconv ( "GBK", "utf-8", $value ) );
					$m ++;
					model_contract_common_orderExcelUtil::toCecter ( $i, $objPHPExcel );
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
		/*************************************����������Ϣ*****************************************/
		//���ñ�ͷ����ʽ ���� -- ���������嵥
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		for($m = 0; $m < 12; $m ++) {
			model_contract_common_orderExcelUtil::tableThead ( $m, $i, '��������', $objPHPExcel );
			$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getBorders ()->getBottom ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
		}
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . ++ $i );
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( 0, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $i - 1, iconv ( "GBK", "utf-8", $dataArr ['warrantyClause'] ) );

		/*************************************�ۺ�Ҫ����Ϣ*****************************************/
		//���ñ�ͷ����ʽ ���� -- �ۺ�Ҫ���嵥
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		for($m = 0; $m < 12; $m ++) {
			model_contract_common_orderExcelUtil::tableThead ( $m, $i, '�ۺ�Ҫ��', $objPHPExcel );
			$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getBorders ()->getBottom ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
		}
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . ++ $i );
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( 0, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $i - 1, iconv ( "GBK", "utf-8", $dataArr ['afterService'] ) );

		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
//		//�������
		ob_end_clean (); //��������������������������
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "��" . $dataArr ['orderName'] . "����ͬ.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );

		//		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
	//
	//		$objWriter = PHPExcel_IOFactory::CreateWriter ( $objPHPExcel );
	//		$objWriter->save ( $fileSave );
	}

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
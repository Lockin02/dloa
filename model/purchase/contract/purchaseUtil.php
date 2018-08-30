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

class model_purchase_contract_purchaseUtil extends model_base {

	//ģ������ʽ
	function tableThead($column,$row,$content,$objPHPExcel){
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $column, $row, iconv ( "gb2312", "utf-8", $content ) );
	}
	//ģ������ʽ
	function tableTrow($column,$row,$content,$objPHPExcel){
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $column, $row, iconv ( "gb2312", "utf-8", $content ) );
	}
	//ģ�����ݾ�����ʽ
	function toCecter( $i,$objPHPExcel ){
		for($temp=0;$temp<12;$temp++){
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $temp, $i )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}
	}

	/**
	 * �����ɹ����������ϵ�EXCEL
	 * @author suxc
	 */
	function exportItemExcel($dataArr) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/purchase_productitem.xls" ); //��ȡģ��
		$excelActiveSheet = $objPHPExcel->getActiveSheet ();

		for($n = 0; $n < count ( $dataArr ); $n ++) {

			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 0, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['suppName'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 1, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['createTime'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 2, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['hwapplyNumb'] ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 3, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['ExaStatus'] ));
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 4, $n + 2, iconv ( "GBK", "utf-8", '' ));
			$excelActiveSheet->setCellValueByColumnAndRow ( 5, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 6, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['pattem'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 7, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['units'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 8, $n + 2, iconv ( "GBK", "utf-8", '�����' ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 9, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['applyDeptName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 10, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['sendName'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 11, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['amountAll'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 12, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['dateHope'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 13, $n + 2, iconv ( "GBK", "utf-8", '' ) );
			$excelActiveSheet->setCellValueExplicitByColumnAndRow ( 14, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['productNumb'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 15, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['applyPrice'] ) );
			$excelActiveSheet->setCellValueByColumnAndRow ( 16, $n + 2, iconv ( "GBK", "utf-8", $dataArr [$n] ['moneyAll'] ) );
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
	 * �����ɹ�����
	 *@param $dataArr �ɹ���������
	 *@param $equRows �ɹ���������
	 */
	function exportPurchaseOrder($dataArr,$equRows){
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/�ɹ���������ģ��.xls" ); //��ȡģ��
		$excelActiveSheet=$objPHPExcel->getActiveSheet ();
		//���ñ�����ļ����Ƽ�·��
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;
		$objPhpExcelFile = new PHPExcel ();
		$sequence=1; //�����嵥���
		foreach($equRows as $key=>$val){
			//��ȡK3����
			$k3Condition="id=".$val['productId'];
			$k3Code=$this->get_table_fields('oa_stock_product_info',$k3Condition,'ext2');

			$equArr[$key]['sequence']=$sequence;
			$equArr[$key]['productNumb']=$equRows[$key]['productNumb'];
			$equArr[$key]['productName']=$equRows[$key]['productName'];
			$equArr[$key]['pattem']=$equRows[$key]['pattem'];
			$equArr[$key]['units']=$equRows[$key]['units'];
			$equArr[$key]['k3Code']=$k3Code;
			$equArr[$key]['amountAll']=$equRows[$key]['amountAll'];
			$equArr[$key]['amountIssued']=$equRows[$key]['amountIssued'];
			$equArr[$key]['dateHope']=$equRows[$key]['dateHope'];
			$equArr[$key]['dateIssued']=$equRows[$key]['dateIssued'];
			$equArr[$key]['applyPrice']=$equRows[$key]['applyPrice'];
			$equArr[$key]['moneyAll']=$equRows[$key]['moneyAll'];
			$equArr[$key]['purchTypeC']=$equRows[$key]['purchTypeC'];
			$equArr[$key]['applyDeptName']=$equRows[$key]['applyDeptName'];
			$equArr[$key]['sourceNumb']=$equRows[$key]['sourceNumb'];
			$equArr[$key]['remark']=$equRows[$key]['remark'];
			$sequence++;

		}
		/*************************************������Ϣ*****************************************/


		for($j = 3; $j <= 12; $j ++) {//��ȡģ���ֶβ��滻
			for($k = 'A'; $k <= 'Q'; $k ++) {
				$ename= iconv ( 'utf-8', 'gb2312', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue ()); //��ȡ��Ԫ��
				if(isset($dataArr[$ename])){
					$pvalue=$dataArr[$ename];
					$objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->setValue(iconv ( "gb2312", "utf-8", $pvalue));
				}
			}
		}
		/*************************************�����嵥��Ϣ*****************************************/
		$i=13;
		//�������
		if (! count ( array_filter ( $equRows) ) == 0) {
			$i ++;
			$row = $i;
			for($n = 0; $n < count ( $equArr ); $n ++) {
				$m = 0;
				foreach ( $equArr [$n] as $field => $value ) {
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0 + $m, $row + $n, iconv ( "gb2312", "utf-8", $value ) );
					$m += 1;
					model_purchase_contract_purchaseUtil::toCecter ( $i, $objPHPExcel );
				}
				$i ++;
			}
		} else {
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':Q' . $i );
			for($m = 0; $m < 17; $m ++) {
				$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '���������Ϣ' ) );
			}
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':Q' . $i );
		}


		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
		//�������
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "�ɹ�����".$dataArr['hwapplyNumb'].".xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );

	}

	/**
	 * �����ɹ�����
	 */
	function exportTask($dataArr){
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/�ɹ����񵼳�ģ��.xls" ); //��ȡģ��
		$excelActiveSheet=$objPHPExcel->getActiveSheet ();
		//���ñ�����ļ����Ƽ�·��
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;
		$objPhpExcelFile = new PHPExcel ();
		$sequence=1; //�����嵥���
		foreach($dataArr["0"]["childArr"] as $key=>$val){
			$equArr[$key]['sequence']=$sequence;
			$equArr[$key]['productNumb']=$dataArr["0"]["childArr"][$key]['productNumb'];
			$equArr[$key]['productName']=$dataArr["0"]["childArr"][$key]['productName'];
			$equArr[$key]['pattem']=$dataArr["0"]["childArr"][$key]['pattem'];
			$equArr[$key]['unitName']=$dataArr["0"]["childArr"][$key]['unitName'];
			$equArr[$key]['amountAll']=$dataArr["0"]["childArr"][$key]['amountAll'];
			$equArr[$key]['amountIssued']=$dataArr["0"]["childArr"][$key]['amountIssued'];
			$equArr[$key]['contractAmount']=$dataArr["0"]["childArr"][$key]['contractAmount'];
			$equArr[$key]['dateHope']=$dataArr["0"]["childArr"][$key]['dateHope'];
			$equArr[$key]['remark']=$dataArr["0"]["childArr"][$key]['remark'];
			$sequence++;

		}
		/*************************************������Ϣ*****************************************/


		for($j = 3; $j <= 9; $j ++) {//��ȡģ���ֶβ��滻
			for($k = 'A'; $k <= 'K'; $k ++) {
				$ename= iconv ( 'utf-8', 'gb2312', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue ()); //��ȡ��Ԫ��
				if(isset($dataArr['0'][$ename])){
					$pvalue=$dataArr['0'][$ename];
					$objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->setValue(iconv ( "gb2312", "utf-8", $pvalue));
				}
			}
		}
		/*************************************�����嵥��Ϣ*****************************************/
		$i=10;
		//�������
		if (! count ( array_filter ( $equArr) ) == 0) {
			$i ++;
			$row = $i;
			for($n = 0; $n < count ( $equArr ); $n ++) {
				$m = 0;
				foreach ( $equArr [$n] as $field => $value ) {
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0 + $m, $row + $n, iconv ( "gb2312", "utf-8", $value ) );
					$m += 1;
					model_purchase_contract_purchaseUtil::toCecter ( $i, $objPHPExcel );
				}
				$i ++;
			}
		} else {
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':K' . $i );
			for($m = 0; $m < 9; $m ++) {
				$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '���������Ϣ' ) );
			}
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':K' . $i );
		}


		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
		//�������
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "�ɹ�����".$dataArr['0']['taskNumb'].".xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );

	}
}
?>

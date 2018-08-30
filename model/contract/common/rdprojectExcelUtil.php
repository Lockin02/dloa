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

class model_contract_common_rdprojectExcelUtil{

	//ģ������ʽ
	function tableThead($column,$row,$content,$objPHPExcel){
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFill ()->getStartColor ()->setARGB ( "00D2DFD6" );
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
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFill ()->getStartColor ()->setARGB ( "00ECF8FB" );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $column, $row, iconv ( "gb2312", "utf-8", $content ) );
	}
	//ģ�����ݾ�����ʽ
	function toCecter( $i,$objPHPExcel ){
		for($temp=0;$temp<12;$temp++){
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $temp, $i )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}
	}

	//��ȡģ�岢����
	public static function exporTemplate($dataArr, $rowdatas,$imageName){
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/rdprojectExport.xls" ); //��ȡģ��
		$excelActiveSheet=$objPHPExcel->getActiveSheet ();
		//���ñ�����ļ����Ƽ�·��
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;
		$objPhpExcelFile = new PHPExcel ();

		//���ñ�ͷ����ʽ ����
		for($m = 0; $m < 12; $m ++) {
			model_contract_common_rdprojectExcelUtil::tableThead($m,1,'��ͬ��Ϣ',$objPHPExcel);
		}
		foreach( $dataArr as $key => $val ){
			if( $val === null ){
				$dataArr[$key][$val] = '';
			}
		}
		//��ȡ�ӱ�����
		//��Ʒ�嵥
		$tempSequence = 1;
		foreach( $dataArr['rdprojectequ'] as $sequence => $equ ){
			$rdprojectequ[$sequence]['sequence']=$tempSequence;
//			$rdprojectequ[$sequence]['productLine']=$dataArr['rdprojectequ'][$sequence]['productLine'];
			$rdprojectequ[$sequence]['productNo']=$dataArr['rdprojectequ'][$sequence]['productNo'];
			$rdprojectequ[$sequence]['productName']=$dataArr['rdprojectequ'][$sequence]['productName'];
			$rdprojectequ[$sequence]['productModel']=$dataArr['rdprojectequ'][$sequence]['productModel'];
			$rdprojectequ[$sequence]['number']=$dataArr['rdprojectequ'][$sequence]['number'];
			$rdprojectequ[$sequence]['price']=$dataArr['rdprojectequ'][$sequence]['price'];
			$rdprojectequ[$sequence]['money']=$dataArr['rdprojectequ'][$sequence]['money'];
			$rdprojectequ[$sequence]['warrantyPeriod']=$dataArr['rdprojectequ'][$sequence]['warrantyPeriod'];
			$rdprojectequ[$sequence]['license']='';
			if($dataArr['rdprojectequ'][$sequence]['isSell']==null){
				$rdprojectequ[$sequence]['isSell']='��';
			}else{
				$rdprojectequ[$sequence]['isSell']='��';
			}
			$tempSequence++;
		}
		//�Զ����Ʒ�嵥
		$tempSequence = 1;
		foreach( $dataArr['customizelist'] as $sequence => $equ ){
			$customizelist[$sequence]['sequence']=$tempSequence;
//			$customizelist[$sequence]['productLine']=$dataArr['customizelist'][$sequence]['productLine'];
			$customizelist[$sequence]['productCode']=$dataArr['customizelist'][$sequence]['productCode'];
			$customizelist[$sequence]['productName']=$dataArr['customizelist'][$sequence]['productName'];
			$customizelist[$sequence]['productModel']=$dataArr['customizelist'][$sequence]['productModel'];
			$customizelist[$sequence]['number']=$dataArr['customizelist'][$sequence]['number'];
			$customizelist[$sequence]['price']=$dataArr['customizelist'][$sequence]['price'];
			$customizelist[$sequence]['money']=$dataArr['customizelist'][$sequence]['money'];
			$customizelist[$sequence]['projArraDT']=$dataArr['customizelist'][$sequence]['projArraDT'];
			$customizelist[$sequence]['remark']=$dataArr['customizelist'][$sequence]['remark'];
			if($dataArr['customizelist'][$sequence]['isSell']==null){
				$customizelist[$sequence]['isSell']='��';
			}else{
				$customizelist[$sequence]['isSell']='��';
			}
			$tempSequence++;
		}
		//��ѵ�ƻ�
		$tempSequence = 1;
		foreach( $dataArr['trainingplan'] as $sequence => $equ ){
			$trainingplan[$sequence]['receiptplan']=$tempSequence;
			$trainingplan[$sequence]['beginDT']=$dataArr['trainingplan'][$sequence]['beginDT'];
			$trainingplan[$sequence]['endDT']=$dataArr['trainingplan'][$sequence]['endDT'];
			$trainingplan[$sequence]['traNum']=$dataArr['trainingplan'][$sequence]['traNum'];
			$trainingplan[$sequence]['adress']=$dataArr['trainingplan'][$sequence]['adress'];
			$trainingplan[$sequence]['content']=$dataArr['trainingplan'][$sequence]['content'];
			$trainingplan[$sequence]['trainer']=$dataArr['trainingplan'][$sequence]['trainer'];
			$tempSequence++;
		}
		/*************************************������Ϣ*****************************************/

			for($j = 3; $j <= 12; $j ++) {//��ȡģ���ֶβ��滻
				for($k = 'A'; $k <= 'K'; $k ++) {
					$ename= iconv ( 'utf-8', 'gb2312', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue ()); //��ȡ��Ԫ��
					if(isset($dataArr[$ename])){
						$pvalue=$dataArr[$ename];
						$objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->setValue(iconv ( "gb2312", "utf-8", $pvalue));
					}
				}
			}
		/*************************************��Ʒ�嵥��Ϣ*****************************************/
			$i=13;
			//���ñ�ͷ����ʽ ���� -- ��Ʒ�嵥
			$objPHPExcel->getActiveSheet ()->mergeCells('A' . $i . ':L' . $i);
			$objPHPExcel->getActiveSheet ()->mergeCells('A' . ++$i . ':L' . $i);
			for($m = 0; $m < 12; $m ++) {
				model_contract_common_rdprojectExcelUtil::tableThead($m,$i,'��Ʒ�嵥',$objPHPExcel);
			}

			//���ñ�ͷ����ʽ ���� -- ��Ʒ�嵥 ����
			$i++;
			$objPHPExcel->getActiveSheet ()->mergeCells('B' . $i . ':C' . $i);
			$objPHPExcel->getActiveSheet ()->mergeCells('D' . $i . ':E' . $i);
			$orderequTr = array('��Ʒ�ͺ�','����','����','���','������(��)','��������','��ͬ��');
			model_contract_common_rdprojectExcelUtil::tableTrow(0,$i,'���',$objPHPExcel);
			for($m = 1; $m < 3; $m ++) {
				model_contract_common_rdprojectExcelUtil::tableTrow($m,$i,'��Ʒ���',$objPHPExcel);
			}
			for($m = 3; $m < 5; $m ++) {
				model_contract_common_rdprojectExcelUtil::tableTrow($m,$i,'��Ʒ����',$objPHPExcel);
			}
			for($m = 5; $m < 12; $m ++) {
				model_contract_common_rdprojectExcelUtil::tableTrow($m,$i,$orderequTr[$m-5],$objPHPExcel);
			}
			//�������
			if( !count(array_filter($dataArr['rdprojectequ']))==0 ){
				$i++;
				$row = $i;
				$tempArr = array(0,1,2,2,1,1,1,1,1,1);
				for($n=0;$n<count($rdprojectequ);$n++){
					$m=0;$sum=0;
					$objPHPExcel->getActiveSheet ()->mergeCells('B' . $i . ':C' . $i);
					$objPHPExcel->getActiveSheet ()->mergeCells('D' . $i . ':E' . $i);
					foreach($rdprojectequ[$n] as $field =>$value){
						$sum += $tempArr[$m];
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0+$sum,$row+$n,iconv("GBK","utf-8",$value));
						$m++;
						model_contract_common_rdprojectExcelUtil::toCecter($i,$objPHPExcel);
					}
					$i++;
				}
			}else{
				$objPHPExcel->getActiveSheet ()->mergeCells('A' . ++$i . ':L' . $i);
				for($m = 0; $m < 12; $m ++) {
					$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '���������Ϣ' ) );
				}
					$objPHPExcel->getActiveSheet ()->mergeCells('A' . ++$i . ':L' . $i);
			}
		/*************************************�Զ����Ʒ�嵥��Ϣ*****************************************/
		$objPHPExcel->getActiveSheet ()->mergeCells('A' . $i . ':L' . $i);
		$objPHPExcel->getActiveSheet ()->mergeCells('A' . ++$i . ':L' . $i);
		for($m = 0; $m < 12; $m ++) {
			model_contract_common_rdprojectExcelUtil::tableThead($m,$i,'�Զ����Ʒ�嵥',$objPHPExcel);
		}

		//���ñ�ͷ����ʽ ���� -- ��Ʒ�嵥 ����
		$i ++;
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'C' . $i . ':D' . $i );
		model_contract_common_rdprojectExcelUtil::tableTrow ( 0, $i, '���', $objPHPExcel );
		model_contract_common_rdprojectExcelUtil::tableTrow ( 1, $i, '��Ʒ���', $objPHPExcel );
		for($m = 2; $m < 4; $m ++) {
			model_contract_common_rdprojectExcelUtil::tableTrow ( $m, $i, '��Ʒ����', $objPHPExcel );
		}
		//���ñ�ͷ����ʽ ���� -- �Զ����Ʒ�嵥 ����
		$orderequTr = array ( '��Ʒ�ͺ�', '����', '����', '���', '�ƻ���������' );
		for($m = 4; $m < 9; $m ++) {
			model_contract_common_rdprojectExcelUtil::tableTrow ( $m, $i, $orderequTr [$m-4], $objPHPExcel );
		}
		//���ñ�ͷ����ʽ ���� -- �Զ����Ʒ�嵥 ����
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'J' . $i . ':K' . $i );
		for($m = 9; $m < 11; $m ++) {
			model_contract_common_rdprojectExcelUtil::tableTrow ( $m, $i, '��ע', $objPHPExcel );
		}
		model_contract_common_rdprojectExcelUtil::tableTrow ( 11, $i, '��ͬ��', $objPHPExcel );

		//�������
		if (! count ( array_filter ( $dataArr ['customizelist'] ) ) == 0) {
			$i ++;
			$row = $i;
			$tempArr = array (0, 1, 1, 2, 1, 1, 1, 1, 1, 2 );
			for($n = 0; $n < count ( $customizelist ); $n ++) {
				$m = 0;
				$sum = 0;
				$objPHPExcel->getActiveSheet ()->mergeCells ( 'C' . $i . ':D' . $i );
				$objPHPExcel->getActiveSheet ()->mergeCells ( 'J' . $i . ':K' . $i );
				foreach ( $customizelist [$n] as $field => $value ) {
					$sum += $tempArr [$m];
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0 + $sum, $row + $n, iconv ( "GBK", "utf-8", $value ) );
					$m ++;
					model_contract_common_rdprojectExcelUtil::toCecter ( $i, $objPHPExcel );
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
			$objPHPExcel->getActiveSheet ()->mergeCells('A' . $i . ':L' . $i);
			$objPHPExcel->getActiveSheet ()->mergeCells('A' . ++$i . ':L' . $i);
			for($m = 0; $m < 12; $m ++) {
				model_contract_common_rdprojectExcelUtil::tableThead($m,$i,'��ѵ�ƻ�',$objPHPExcel);
			}

			//���ñ�ͷ����ʽ ���� --��ѵ�ƻ��嵥 ����
			$i++;
			$orderequTr = array('���','��ѵ��ʼʱ��','��ѵ����ʱ��','��������');
			$objPHPExcel->getActiveSheet ()->mergeCells('E' . $i . ':F' . $i);
			$objPHPExcel->getActiveSheet ()->mergeCells('G' . $i . ':I' . $i);
			$objPHPExcel->getActiveSheet ()->mergeCells('J' . $i . ':L' . $i);
			for($m = 0; $m < 4; $m ++) {
				model_contract_common_rdprojectExcelUtil::tableTrow($m,$i,$orderequTr[$m],$objPHPExcel);
			}
			for($m = 4; $m < 6; $m ++) {
				model_contract_common_rdprojectExcelUtil::tableTrow($m,$i,'��ѵ�ص�',$objPHPExcel);
			}
			for($m = 6; $m < 9; $m ++) {
				model_contract_common_rdprojectExcelUtil::tableTrow($m,$i,'��ѵ����',$objPHPExcel);
			}
			for($m = 9; $m < 12; $m ++) {
				model_contract_common_rdprojectExcelUtil::tableTrow($m,$i,'��ѵ����ʦҪ��',$objPHPExcel);
			}
			//�������
			if( !count(array_filter($dataArr['trainingplan']))==0 ){
				$i++;
				$row = $i;
				$tempArr = array(0,1,1,1,1,2,3);
				for($n=0;$n<count($trainingplan);$n++){
					$m=0;$sum=0;
					$objPHPExcel->getActiveSheet ()->mergeCells('E' . $i . ':F' . $i);
					$objPHPExcel->getActiveSheet ()->mergeCells('G' . $i . ':I' . $i);
					$objPHPExcel->getActiveSheet ()->mergeCells('J' . $i . ':L' . $i);
					foreach($trainingplan[$n] as $field =>$value){
						$sum += $tempArr[$m];
						$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(0+$sum,$row+$n,iconv("GBK","utf-8",$value));
						$m++;
						model_contract_common_rdprojectExcelUtil::toCecter($i,$objPHPExcel);
					}
					$i++;
				}
			}else{
				$objPHPExcel->getActiveSheet ()->mergeCells('A' . ++$i . ':L' . $i);
				for($m = 0; $m < 12; $m ++) {
					$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '���������Ϣ' ) );
				}
			}

		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
				//�������
		ob_end_clean();//��������������������������
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "��".$dataArr['orderName']."����ͬ.xls" . '"' );
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

}
?>
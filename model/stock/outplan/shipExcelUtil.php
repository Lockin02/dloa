<?php
/**
 * @description: ʵ�����ݵ�����Excel�Ĺ���
 * @date 2010-10-20 ����07:18:35
 */
include_once "module/phpExcel/classes/PHPExcel.php";
include_once "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include_once "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include_once "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include_once "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";

class model_stock_outplan_shipExcelUtil{

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

	//��ȡģ�岢����
	public static function exporTemplate($dataArr, $rowdatas,$imageName){
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/����������ģ��.xls" ); //��ȡģ��
		$excelActiveSheet=$objPHPExcel->getActiveSheet ();
		//���ñ�����ļ����Ƽ�·��
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;
		$objPhpExcelFile = new PHPExcel ();

		$linkmanInfo = "��ϵ�ˣ� " . $dataArr['linkman'] . "        �ֻ��� " . $dataArr['mobil'] . "        �ʱࣺ " . $dataArr['postCode'];
		$dataArr['linkmanInfo'] = $linkmanInfo;

		switch($dataArr['shipType']){
			case 'order' : $shipType = '���� ����           �� ���           �� ����             �� ����           �� ����';
				break;
			case 'borrow' : $shipType = '�� ����           ���� ���           �� ����             �� ����           �� ����';
				break;
			case 'lease' : $shipType = '�� ����           �� ���           ���� ����             �� ����           �� ����';
				break;
			case 'trial' : $shipType = '�� ����           �� ���           �� ����             ���� ����           �� ����';
				break;
			case 'change' : $shipType = '�� ����           �� ���           �� ����             �� ����           ���� ����';
				break;
		}
		//���ñ�ͷ����ʽ ����
		model_stock_outplan_shipExcelUtil::tableThead(0,9,$shipType,$objPHPExcel);
		foreach( $dataArr as $key => $val ){
			if( $val === null ){
				$dataArr[$key] = '';
			}
		}
		//��ȡ�ӱ�����
		//��ѵ�ƻ�
		$tempSequence = 1;
		foreach( $dataArr['details'] as $sequence => $equ ){
			$details[$sequence]['sequence']=$tempSequence;
			$details[$sequence]['productName']=$dataArr['details'][$sequence]['productName'];
			$details[$sequence]['contNum']=$dataArr['details'][$sequence]['contNum'];
			$details[$sequence]['number']=$dataArr['details'][$sequence]['number'];
			$details[$sequence]['remark']=$dataArr['details'][$sequence]['remark'];
			$tempSequence++;
		}
		/*************************************������Ϣ*****************************************/

			for($j = 10; $j <= 16; $j ++) {//��ȡģ���ֶβ��滻
				for($k = 'A'; $k <= 'C'; $k ++) {
					$ename= iconv ( 'utf-8', 'gb2312', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue ()); //��ȡ��Ԫ��
					if(isset($dataArr[$ename])){
						$pvalue=$dataArr[$ename];
						$objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->setValue(iconv ( "gb2312", "utf-8", $pvalue));
					}
				}
			}
		/*************************************��Ʒ�嵥��Ϣ*****************************************/

		$objPHPExcel->getActiveSheet ()->mergeCells('A' . 17 . ':E' . 17);
		$objPHPExcel->getActiveSheet ()->mergeCells('A' . 18 . ':E' . 18);
		$orderequTr = array('���','��Ʒ����','��ͬ����','��ǰʵ������','��ע');
		model_stock_outplan_shipExcelUtil::tableThead(0,18,'�����嵥',$objPHPExcel);
		for($m = 0; $m < 5; $m ++) {
			model_stock_outplan_shipExcelUtil::tableTrow($m,19,$orderequTr[$m],$objPHPExcel);
		}
		//�������
		$i=20;
		if( !count(array_filter($details))==0 ){
			$row = $i;
			for($n=0;$n<count($details);$n++){
				$m=0;
				foreach($details[$n] as $field =>$value){
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow($m,$row+$n,iconv("gb2312","utf-8",$value));
					$m++;
					model_stock_outplan_shipExcelUtil::toCecter($i,$objPHPExcel);
				}
				$i++;
			}
		}else{
			$objPHPExcel->getActiveSheet ()->mergeCells('A' . ++$i . ':E' . $i);
			for($m = 0; $m < 12; $m ++) {
				$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '���������Ϣ' ) );
			}
		}

		$objPHPExcel->getActiveSheet ()->mergeCells('A' . $i . ':E' . $i);

		$objPHPExcel->getActiveSheet ()->mergeCells('B' . ++$i . ':E' . $i);
			$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( 1, $i )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $i, iconv ( "gb2312", "utf-8", '��ע��' ) );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $i, iconv ( "gb2312", "utf-8", $dataArr['remark'] ) );

		$objPHPExcel->getActiveSheet ()->mergeCells('A' . ++$i . ':E' . $i);
		$additionalInfo = "�����ˣ� " . $dataArr['outstockman'] . "              �����ˣ�" . $dataArr['shipman'] . "              �����: " . $dataArr['auditman'];

		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( 0, $i )->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $i, iconv ( "gb2312", "utf-8", $additionalInfo ) );

		$objPHPExcel->getActiveSheet ()->mergeCells('A' . ++$i . ':E' . $i);
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( 0, $i )->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $i, iconv ( "gb2312", "utf-8", '�������ڣ�'.$dataArr['shipDate'] ) );

		$objPHPExcel->getActiveSheet ()->mergeCells('A' . ++$i . ':C' . $i);
		$objPHPExcel->getActiveSheet ()->mergeCells('D' . $i . ':E' . $i);
		for($m=0;$m<5;$m++){
			$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getFont()->setSize(12);
		}
		$signDate = $dataArr['signDate'];
		$signman = $dataArr['signman'];
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $i, iconv ( "gb2312", "utf-8", 'ǩ�����ڣ�'.$signDate ) );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $i, iconv ( "gb2312", "utf-8", 'ǩ���ˣ�'.$signman ) );

		$foot = "��ӭʹ���ҹ�˾�Ĳ�Ʒ�����˾�ջ����յ�������������������ǩ�������ص���������0756-3630389����ɨ����ʼ���jin.yang@dinglicom.com;�����������ʣ����µ������֣��꣺0756-3626024��ά���������ʣ����µ�λ�0756��3626022��";

		$objPHPExcel->getActiveSheet ()->mergeCells('A' . ++$i . ':E' . $i);
		$objPHPExcel->getActiveSheet ()->mergeCells('A' . ++$i . ':E' . $i);
		//�Զ�����
		$objPHPExcel->getActiveSheet()->getStyle('A'.($i))->getAlignment()->setWrapText(true);
		//�����иߣ��п���setWidth()��
		$objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(40);
		model_stock_outplan_shipExcelUtil::tableThead(0,$i,$foot,$objPHPExcel);

		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
		//�������
		ob_end_clean();//��������������������������
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "��������".$dataArr['shipCode']."��.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );

		//������
//		$objWriter = PHPExcel_IOFactory::CreateWriter ( $objPHPExcel );
//		$objWriter->save ( $fileSave );

	}
}
?>
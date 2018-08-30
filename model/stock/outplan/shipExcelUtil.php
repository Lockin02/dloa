<?php
/**
 * @description: 实现数据导出到Excel的功能
 * @date 2010-10-20 下午07:18:35
 */
include_once "module/phpExcel/classes/PHPExcel.php";
include_once "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include_once "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include_once "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include_once "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";

class model_stock_outplan_shipExcelUtil{

	//模块名样式
	function tableThead($column,$row,$content,$objPHPExcel){
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $column, $row, iconv ( "gb2312", "utf-8", $content ) );
	}
	//模块列样式
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
	//模块内容居中样式
	function toCecter( $i,$objPHPExcel ){
		for($temp=0;$temp<12;$temp++){
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $temp, $i )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}
	}

	//读取模板并导出
	public static function exporTemplate($dataArr, $rowdatas,$imageName){
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/发货单导出模板.xls" ); //读取模板
		$excelActiveSheet=$objPHPExcel->getActiveSheet ();
		//设置保存的文件名称及路径
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;
		$objPhpExcelFile = new PHPExcel ();

		$linkmanInfo = "联系人： " . $dataArr['linkman'] . "        手机： " . $dataArr['mobil'] . "        邮编： " . $dataArr['postCode'];
		$dataArr['linkmanInfo'] = $linkmanInfo;

		switch($dataArr['shipType']){
			case 'order' : $shipType = '□√ 发货           □ 借货           □ 租用             □ 试用           □ 更换';
				break;
			case 'borrow' : $shipType = '□ 发货           □√ 借货           □ 租用             □ 试用           □ 更换';
				break;
			case 'lease' : $shipType = '□ 发货           □ 借货           □√ 租用             □ 试用           □ 更换';
				break;
			case 'trial' : $shipType = '□ 发货           □ 借货           □ 租用             □√ 试用           □ 更换';
				break;
			case 'change' : $shipType = '□ 发货           □ 借货           □ 租用             □ 试用           □√ 更换';
				break;
		}
		//设置表头及样式 设置
		model_stock_outplan_shipExcelUtil::tableThead(0,9,$shipType,$objPHPExcel);
		foreach( $dataArr as $key => $val ){
			if( $val === null ){
				$dataArr[$key] = '';
			}
		}
		//获取从表数据
		//培训计划
		$tempSequence = 1;
		foreach( $dataArr['details'] as $sequence => $equ ){
			$details[$sequence]['sequence']=$tempSequence;
			$details[$sequence]['productName']=$dataArr['details'][$sequence]['productName'];
			$details[$sequence]['contNum']=$dataArr['details'][$sequence]['contNum'];
			$details[$sequence]['number']=$dataArr['details'][$sequence]['number'];
			$details[$sequence]['remark']=$dataArr['details'][$sequence]['remark'];
			$tempSequence++;
		}
		/*************************************主表信息*****************************************/

			for($j = 10; $j <= 16; $j ++) {//读取模板字段并替换
				for($k = 'A'; $k <= 'C'; $k ++) {
					$ename= iconv ( 'utf-8', 'gb2312', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue ()); //读取单元格
					if(isset($dataArr[$ename])){
						$pvalue=$dataArr[$ename];
						$objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->setValue(iconv ( "gb2312", "utf-8", $pvalue));
					}
				}
			}
		/*************************************产品清单信息*****************************************/

		$objPHPExcel->getActiveSheet ()->mergeCells('A' . 17 . ':E' . 17);
		$objPHPExcel->getActiveSheet ()->mergeCells('A' . 18 . ':E' . 18);
		$orderequTr = array('序号','产品名称','合同数量','当前实发数量','备注');
		model_stock_outplan_shipExcelUtil::tableThead(0,18,'物料清单',$objPHPExcel);
		for($m = 0; $m < 5; $m ++) {
			model_stock_outplan_shipExcelUtil::tableTrow($m,19,$orderequTr[$m],$objPHPExcel);
		}
		//数据输出
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
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
			}
		}

		$objPHPExcel->getActiveSheet ()->mergeCells('A' . $i . ':E' . $i);

		$objPHPExcel->getActiveSheet ()->mergeCells('B' . ++$i . ':E' . $i);
			$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( 1, $i )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $i, iconv ( "gb2312", "utf-8", '备注：' ) );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $i, iconv ( "gb2312", "utf-8", $dataArr['remark'] ) );

		$objPHPExcel->getActiveSheet ()->mergeCells('A' . ++$i . ':E' . $i);
		$additionalInfo = "出库人： " . $dataArr['outstockman'] . "              发货人：" . $dataArr['shipman'] . "              审核人: " . $dataArr['auditman'];

		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( 0, $i )->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $i, iconv ( "gb2312", "utf-8", $additionalInfo ) );

		$objPHPExcel->getActiveSheet ()->mergeCells('A' . ++$i . ':E' . $i);
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( 0, $i )->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $i, iconv ( "gb2312", "utf-8", '发货日期：'.$dataArr['shipDate'] ) );

		$objPHPExcel->getActiveSheet ()->mergeCells('A' . ++$i . ':C' . $i);
		$objPHPExcel->getActiveSheet ()->mergeCells('D' . $i . ':E' . $i);
		for($m=0;$m<5;$m++){
			$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getFont()->setSize(12);
		}
		$signDate = $dataArr['signDate'];
		$signman = $dataArr['signman'];
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $i, iconv ( "gb2312", "utf-8", '签收日期：'.$signDate ) );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $i, iconv ( "gb2312", "utf-8", '签收人：'.$signman ) );

		$foot = "欢迎使用我公司的产品！请贵司收货人收到货后于三个工作日内签返发货回单，传真至0756-3630389，或扫描后发邮件至jin.yang@dinglicom.com;发货如有疑问，请致电杨锦或郑娟娟：0756-3626024，维修如有疑问，请致电何花0756－3626022；";

		$objPHPExcel->getActiveSheet ()->mergeCells('A' . ++$i . ':E' . $i);
		$objPHPExcel->getActiveSheet ()->mergeCells('A' . ++$i . ':E' . $i);
		//自动换行
		$objPHPExcel->getActiveSheet()->getStyle('A'.($i))->getAlignment()->setWrapText(true);
		//设置行高（列宽用setWidth()）
		$objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(40);
		model_stock_outplan_shipExcelUtil::tableThead(0,$i,$foot,$objPHPExcel);

		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
		//到浏览器
		ob_end_clean();//解决输出到浏览器出现乱码的问题
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "发货单《".$dataArr['shipCode']."》.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );

		//到本地
//		$objWriter = PHPExcel_IOFactory::CreateWriter ( $objPHPExcel );
//		$objWriter->save ( $fileSave );

	}
}
?>
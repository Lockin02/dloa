<?php
/**
 * @description: 实现数据导出到Excel的功能
 * @date 2010-10-20 下午07:18:35
 */

include "module/phpExcel/classes/PHPExcel.php";
include "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";

class model_contract_common_rdprojectExcelUtil{

	//模块名样式
	function tableThead($column,$row,$content,$objPHPExcel){
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFill ()->getStartColor ()->setARGB ( "00D2DFD6" );
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
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFill ()->getStartColor ()->setARGB ( "00ECF8FB" );
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
		$objPHPExcel = $objReader->load ( "upfile/rdprojectExport.xls" ); //读取模板
		$excelActiveSheet=$objPHPExcel->getActiveSheet ();
		//设置保存的文件名称及路径
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;
		$objPhpExcelFile = new PHPExcel ();

		//设置表头及样式 设置
		for($m = 0; $m < 12; $m ++) {
			model_contract_common_rdprojectExcelUtil::tableThead($m,1,'合同信息',$objPHPExcel);
		}
		foreach( $dataArr as $key => $val ){
			if( $val === null ){
				$dataArr[$key][$val] = '';
			}
		}
		//获取从表数据
		//产品清单
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
				$rdprojectequ[$sequence]['isSell']='否';
			}else{
				$rdprojectequ[$sequence]['isSell']='是';
			}
			$tempSequence++;
		}
		//自定义产品清单
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
				$customizelist[$sequence]['isSell']='否';
			}else{
				$customizelist[$sequence]['isSell']='是';
			}
			$tempSequence++;
		}
		//培训计划
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
		/*************************************主表信息*****************************************/

			for($j = 3; $j <= 12; $j ++) {//读取模板字段并替换
				for($k = 'A'; $k <= 'K'; $k ++) {
					$ename= iconv ( 'utf-8', 'gb2312', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue ()); //读取单元格
					if(isset($dataArr[$ename])){
						$pvalue=$dataArr[$ename];
						$objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->setValue(iconv ( "gb2312", "utf-8", $pvalue));
					}
				}
			}
		/*************************************产品清单信息*****************************************/
			$i=13;
			//设置表头及样式 设置 -- 产品清单
			$objPHPExcel->getActiveSheet ()->mergeCells('A' . $i . ':L' . $i);
			$objPHPExcel->getActiveSheet ()->mergeCells('A' . ++$i . ':L' . $i);
			for($m = 0; $m < 12; $m ++) {
				model_contract_common_rdprojectExcelUtil::tableThead($m,$i,'产品清单',$objPHPExcel);
			}

			//设置表头及样式 设置 -- 产品清单 列名
			$i++;
			$objPHPExcel->getActiveSheet ()->mergeCells('B' . $i . ':C' . $i);
			$objPHPExcel->getActiveSheet ()->mergeCells('D' . $i . ':E' . $i);
			$orderequTr = array('产品型号','数量','单价','金额','保修期(月)','加密配置','合同内');
			model_contract_common_rdprojectExcelUtil::tableTrow(0,$i,'序号',$objPHPExcel);
			for($m = 1; $m < 3; $m ++) {
				model_contract_common_rdprojectExcelUtil::tableTrow($m,$i,'产品编号',$objPHPExcel);
			}
			for($m = 3; $m < 5; $m ++) {
				model_contract_common_rdprojectExcelUtil::tableTrow($m,$i,'产品名称',$objPHPExcel);
			}
			for($m = 5; $m < 12; $m ++) {
				model_contract_common_rdprojectExcelUtil::tableTrow($m,$i,$orderequTr[$m-5],$objPHPExcel);
			}
			//数据输出
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
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
				}
					$objPHPExcel->getActiveSheet ()->mergeCells('A' . ++$i . ':L' . $i);
			}
		/*************************************自定义产品清单信息*****************************************/
		$objPHPExcel->getActiveSheet ()->mergeCells('A' . $i . ':L' . $i);
		$objPHPExcel->getActiveSheet ()->mergeCells('A' . ++$i . ':L' . $i);
		for($m = 0; $m < 12; $m ++) {
			model_contract_common_rdprojectExcelUtil::tableThead($m,$i,'自定义产品清单',$objPHPExcel);
		}

		//设置表头及样式 设置 -- 产品清单 列名
		$i ++;
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'C' . $i . ':D' . $i );
		model_contract_common_rdprojectExcelUtil::tableTrow ( 0, $i, '序号', $objPHPExcel );
		model_contract_common_rdprojectExcelUtil::tableTrow ( 1, $i, '产品编号', $objPHPExcel );
		for($m = 2; $m < 4; $m ++) {
			model_contract_common_rdprojectExcelUtil::tableTrow ( $m, $i, '产品名称', $objPHPExcel );
		}
		//设置表头及样式 设置 -- 自定义产品清单 列名
		$orderequTr = array ( '产品型号', '数量', '单价', '金额', '计划交货日期' );
		for($m = 4; $m < 9; $m ++) {
			model_contract_common_rdprojectExcelUtil::tableTrow ( $m, $i, $orderequTr [$m-4], $objPHPExcel );
		}
		//设置表头及样式 设置 -- 自定义产品清单 列名
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'J' . $i . ':K' . $i );
		for($m = 9; $m < 11; $m ++) {
			model_contract_common_rdprojectExcelUtil::tableTrow ( $m, $i, '备注', $objPHPExcel );
		}
		model_contract_common_rdprojectExcelUtil::tableTrow ( 11, $i, '合同内', $objPHPExcel );

		//数据输出
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
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
			}
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		}
		/*************************************培训计划信息*****************************************/

			//设置表头及样式 设置 -- 培训计划清单
			$objPHPExcel->getActiveSheet ()->mergeCells('A' . $i . ':L' . $i);
			$objPHPExcel->getActiveSheet ()->mergeCells('A' . ++$i . ':L' . $i);
			for($m = 0; $m < 12; $m ++) {
				model_contract_common_rdprojectExcelUtil::tableThead($m,$i,'培训计划',$objPHPExcel);
			}

			//设置表头及样式 设置 --培训计划清单 列名
			$i++;
			$orderequTr = array('序号','培训开始时间','培训结束时间','参与人数');
			$objPHPExcel->getActiveSheet ()->mergeCells('E' . $i . ':F' . $i);
			$objPHPExcel->getActiveSheet ()->mergeCells('G' . $i . ':I' . $i);
			$objPHPExcel->getActiveSheet ()->mergeCells('J' . $i . ':L' . $i);
			for($m = 0; $m < 4; $m ++) {
				model_contract_common_rdprojectExcelUtil::tableTrow($m,$i,$orderequTr[$m],$objPHPExcel);
			}
			for($m = 4; $m < 6; $m ++) {
				model_contract_common_rdprojectExcelUtil::tableTrow($m,$i,'培训地点',$objPHPExcel);
			}
			for($m = 6; $m < 9; $m ++) {
				model_contract_common_rdprojectExcelUtil::tableTrow($m,$i,'培训内容',$objPHPExcel);
			}
			for($m = 9; $m < 12; $m ++) {
				model_contract_common_rdprojectExcelUtil::tableTrow($m,$i,'培训工程师要求',$objPHPExcel);
			}
			//数据输出
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
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
				}
			}

		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
				//到浏览器
		ob_end_clean();//解决输出到浏览器出现乱码的问题
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "《".$dataArr['orderName']."》合同.xls" . '"' );
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
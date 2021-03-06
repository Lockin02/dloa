<?php
/**
 * @description: 仓存管理excel操作util
 * @date 2011-07-23
 */

include "module/phpExcel/classes/PHPExcel.php";
include "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include "module/phpExcel/Classes/PHPExcel/Cell.php";
include "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";

class model_outsourcing_workverify_workVerifyUtil extends model_base {

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


	/**
	 * 导出工作量确认单
	 *@param $dataArr 确认单数据
	 *@param $equRows 详细数据
	 */
	function exportWorkVerify($dataArr,$equRows){
		set_time_limit(0);
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/工作量确认单导出模板.xls" ); //读取模板
		$excelActiveSheet=$objPHPExcel->getActiveSheet ();
		//设置保存的文件名称及路径
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;
		$objPhpExcelFile = new PHPExcel ();
		$sequence=1; //物料清单序号
		foreach($equRows as $key=>$val){
			$equArr[$key]['num']=$sequence;
			$equArr[$key]['officeName']=$equRows[$key]['officeName'];
			$equArr[$key]['province']=$equRows[$key]['province'];
			$equArr[$key]['outsourcingName']=$equRows[$key]['outsourcingName'];
			$equArr[$key]['outsourcingName']=$equRows[$key]['outsourcingName'];
			$equArr[$key]['projectName']=$equRows[$key]['projectName'];
			$equArr[$key]['projectCode']=$equRows[$key]['projectCode'];
			$equArr[$key]['outsourceContractCode']=$equRows[$key]['outsourceContractCode'];
			$equArr[$key]['outsourceSupp']=$equRows[$key]['outsourceSupp'];
			$equArr[$key]['principal']=$equRows[$key]['principal'];
			$equArr[$key]['userName']=$equRows[$key]['userName'];
			$equArr[$key]['beginDate']=$equRows[$key]['beginDate'];
			$equArr[$key]['endDate']=$equRows[$key]['endDate'];
			$equArr[$key]['feeDay']=$equRows[$key]['feeDay'];
			$equArr[$key]['beginDatePM']=$equRows[$key]['beginDatePM'];
			$equArr[$key]['endDatePM']=$equRows[$key]['endDatePM'];
			$equArr[$key]['feeDayPM']=$equRows[$key]['feeDayPM'];
			$sequence++;

		}
		/*************************************主表信息*****************************************/


		for($j = 0; $j <= 1; $j ++) {//读取模板字段并替换
			for($k = 'A'; $k <= 'Q'; $k ++) {
				$ename= iconv ( 'utf-8', 'gb2312', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue ()); //读取单元格
				if(isset($dataArr[$ename])){
					$pvalue=$dataArr[$ename];
					$objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->setValue(iconv ( "gb2312", "utf-8", $pvalue));
				}
			}
		}
		/*************************************物料清单信息*****************************************/
		$i=2;
		//数据输出
		if (! count ( array_filter ( $equRows) ) == 0) {
			$i ++;
			$row = $i;
			for($n = 0; $n < count ( $equArr ); $n ++) {
				$m = 0;
				foreach ( $equArr [$n] as $field => $value ) {
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0 + $m, $row + $n, iconv ( "gb2312", "utf-8", $value ) );
					$m += 1;
					model_outsourcing_workverify_workVerifyUtil::toCecter ( $i, $objPHPExcel );
				}
				$i ++;
			}
		} else {
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':N' . $i );
			for($m = 0; $m < 17; $m ++) {
				$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
			}
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':N' . $i );
		}


		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
		//到浏览器
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "工作量确认单".$dataArr['beginDate']."~".$dataArr['endDate'].".xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );

	}
}
?>

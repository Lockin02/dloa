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

class model_contract_common_orderExcelUtil {

	//模块名样式
	function tableThead($column, $row, $content, $objPHPExcel) {
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFont ()->setSize ( 12 );
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFont ()->setBold ( true );
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $column, $row )->getFill ()->getStartColor ()->setARGB ( "00D2DFD6" );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $column, $row, iconv ( "gb2312", "utf-8", $content ) );
	}
	//模块列样式
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
	//模块内容居中样式
	function toCecter($i, $objPHPExcel) {
		for($temp = 0; $temp < 12; $temp ++) {
			$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $temp, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		}
	}

	//读取模板并导出
	public static function exporTemplate($dataArr, $rowdatas, $imageName) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/contExport.xls" ); //读取模板
		$excelActiveSheet = $objPHPExcel->getActiveSheet ();
		//设置保存的文件名称及路径
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;
		$objPhpExcelFile = new PHPExcel ();

		//设置表头及样式 设置
		for($m = 0; $m < 12; $m ++) {
			model_contract_common_orderExcelUtil::tableThead ( $m, 1, '合同信息', $objPHPExcel );
		}
		foreach ( $dataArr as $key => $val ) {
			if ($val === null) {
				$dataArr [$key] [$val] = '';
			}
		}
		//获取从表数据
		//合同联系人
		$tempSequence = 1;
		foreach ( $dataArr ['linkman'] as $sequence => $equ ) {
			$linkman [$sequence] ['sequence'] = $tempSequence;
			$linkman [$sequence] ['linkman'] = $dataArr ['linkman'] [$sequence] ['linkman'];
			$linkman [$sequence] ['telephone'] = $dataArr ['linkman'] [$sequence] ['telephone'];
			$linkman [$sequence] ['email'] = $dataArr ['linkman'] [$sequence] ['email'];
			$linkman [$sequence] ['remark'] = $dataArr ['linkman'] [$sequence] ['remark'];
			$tempSequence ++;
		}
		//产品清单
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
				$orderequ [$sequence] ['isSell'] = '否';
			} else {
				$orderequ [$sequence] ['isSell'] = '是';
			}
			$tempSequence ++;
		}
		echo "<pre>";
		print_R($orderequ);
		//自定义产品清单
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
				$customizelist [$sequence] ['isSell'] = '否';
			} else {
				$customizelist [$sequence] ['isSell'] = '是';
			}
			$tempSequence ++;
		}
		//开票计划
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
		//收款计划
		$tempSequence = 1;
		foreach ( $dataArr ['receiptplan'] as $sequence => $equ ) {
			$receiptplan [$sequence] ['receiptplan'] = $tempSequence;
			$receiptplan [$sequence] ['money'] = $dataArr ['receiptplan'] [$sequence] ['money'];
			$receiptplan [$sequence] ['payDT'] = $dataArr ['receiptplan'] [$sequence] ['payDT'];
			$receiptplan [$sequence] ['pType'] = $dataArr ['receiptplan'] [$sequence] ['pType'];
			$receiptplan [$sequence] ['collectionTerms'] = $dataArr ['receiptplan'] [$sequence] ['collectionTerms'];
			$tempSequence ++;
		}
		//培训计划
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
		/*************************************主表信息*****************************************/

		for($j = 3; $j <= 10; $j ++) { //读取模板字段并替换
			for($k = 'A'; $k <= 'K'; $k ++) {
				$ename = iconv ( 'utf-8', 'gb2312', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue () ); //读取单元格
				if (isset ( $dataArr [$ename] )) {
					$pvalue = $dataArr [$ename];
					$objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->setValue ( iconv ( "gb2312", "utf-8", $pvalue ) );
				}
			}
		}
		/*************************************合同联系人信息*****************************************/
		$i = 11;
		//设置表头及样式 设置 -- 联系人
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A11:L11' );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A12:L12' );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'B13:C13' );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'D13:F13' );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'G13:I13' );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'J13:L13' );
		for($m = 0; $m < 12; $m ++) {
			$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, 10 )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
			$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, 10 )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			model_contract_common_orderExcelUtil::tableThead ( $m, 11, '客户联系人', $objPHPExcel );
		}
		//设置表头及样式 设置 -- 联系人 序号
		model_contract_common_orderExcelUtil::tableTrow ( 0, 12, '序号', $objPHPExcel );
		//设置表头及样式 设置 -- 联系人 联系人名称
		for($m = 1; $m < 3; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, 12, '客户联系人', $objPHPExcel );
		}
		//设置表头及样式 设置 -- 联系人 电话
		for($m = 3; $m < 6; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, 12, '电话', $objPHPExcel );
		}
		//设置表头及样式 设置 -- 联系人 邮件
		for($m = 6; $m < 9; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, 12, '邮件', $objPHPExcel );
		}
		//设置表头及样式 设置 -- 联系人 联系人名称
		for($m = 9; $m < 12; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, 12, '备注', $objPHPExcel );
		}
		//数据输出
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
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
			}
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		}
		/*************************************产品清单信息*****************************************/

		//设置表头及样式 设置 -- 产品清单
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		model_contract_common_orderExcelUtil::tableThead ( 0, $i, '产品清单', $objPHPExcel );

		//设置表头及样式 设置 -- 产品清单 列名
		$i ++;
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'C' . $i . ':D' . $i );
		model_contract_common_orderExcelUtil::tableTrow ( 0, $i, '序号', $objPHPExcel );
		model_contract_common_orderExcelUtil::tableTrow ( 1, $i, '产品编号', $objPHPExcel );
		for($m = 2; $m < 4; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '产品名称', $objPHPExcel );
		}
		$orderequTr = array ( '产品型号', '数量', '单价', '金额', '计划交货日期', '保修期', '加密配置', '合同内' );
		for($m = 4; $m < 12; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, $orderequTr [$m-4], $objPHPExcel );
		}
		//数据输出
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
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
			}
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		}
		/*************************************自定义产品清单信息*****************************************/

		//设置表头及样式 设置 -- 自定义产品清单
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		model_contract_common_orderExcelUtil::tableThead ( 0, $i, '自定义产品清单', $objPHPExcel );
		$i ++;
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'C' . $i . ':D' . $i );
		model_contract_common_orderExcelUtil::tableTrow ( 0, $i, '序号', $objPHPExcel );
		model_contract_common_orderExcelUtil::tableTrow ( 1, $i, '产品编号', $objPHPExcel );
		for($m = 2; $m < 4; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '产品名称', $objPHPExcel );
		}
		//设置表头及样式 设置 -- 自定义产品清单 列名
		$orderequTr = array ( '产品型号', '数量', '单价', '金额', '计划交货日期' );
		for($m = 4; $m < 9; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, $orderequTr [$m-4], $objPHPExcel );
		}
		//设置表头及样式 设置 -- 自定义产品清单 列名
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'J' . $i . ':K' . $i );
		for($m = 9; $m < 11; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '备注', $objPHPExcel );
		}
		model_contract_common_orderExcelUtil::tableTrow ( 11, $i, '合同内', $objPHPExcel );

		//数据输出
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
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
			}
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		}
		/*************************************开票计划信息*****************************************/
		//设置表头及样式 设置 -- 开票计划
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		model_contract_common_orderExcelUtil::tableThead ( 0, $i, '开票计划', $objPHPExcel );
		$i ++;
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'B' . $i . ':C' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'D' . $i . ':E' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'F' . $i . ':G' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'H' . $i . ':I' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'J' . $i . ':L' . $i );
		//设置表头及样式 设置 -- 开票计划 列名
		model_contract_common_orderExcelUtil::tableTrow ( 0, $i, '序号', $objPHPExcel );
		for($m = 1; $m < 3; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '开票金额', $objPHPExcel );
		}
		for($m = 3; $m < 5; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '其中软件金额', $objPHPExcel );
		}
		for($m = 5; $m < 7; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '开票类型', $objPHPExcel );
		}
		for($m = 7; $m < 9; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '开票日期', $objPHPExcel );
		}
		for($m = 9; $m < 12; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '开票内容', $objPHPExcel );
		}
		//数据输出
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
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
			}
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		}
		/*************************************收款计划信息*****************************************/
		//设置表头及样式 设置 -- 收款计划 列名
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		model_contract_common_orderExcelUtil::tableThead ( 0, $i, '收款计划', $objPHPExcel );
		$i ++;
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'B' . $i . ':C' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'D' . $i . ':E' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'F' . $i . ':G' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'H' . $i . ':L' . $i );
		model_contract_common_orderExcelUtil::tableTrow ( 0, $i, '序号', $objPHPExcel );
		for($m = 1; $m < 3; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '收款金额', $objPHPExcel );
		}
		for($m = 3; $m < 5; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '收款日期', $objPHPExcel );
		}
		for($m = 5; $m < 7; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '收款方式', $objPHPExcel );
		}
		for($m = 7; $m < 12; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '收款条件', $objPHPExcel );
		}
		//数据输出
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
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
			}
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		}
		/*************************************培训计划信息*****************************************/

		//设置表头及样式 设置 -- 培训计划清单
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		model_contract_common_orderExcelUtil::tableThead ( 0, $i, '培训计划', $objPHPExcel );

		//设置表头及样式 设置 --培训计划清单 列名
		$orderequTr = array ('序号', '培训开始时间', '培训结束时间', '参与人数' );
		$i ++;
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'E' . $i . ':F' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'G' . $i . ':I' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'J' . $i . ':L' . $i );
		for($m = 0; $m < 4; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, $orderequTr [$m], $objPHPExcel );
		}
		for($m = 4; $m < 6; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '培训地点', $objPHPExcel );
		}
		for($m = 6; $m < 9; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '培训内容', $objPHPExcel );
		}
		for($m = 9; $m < 12; $m ++) {
			model_contract_common_orderExcelUtil::tableTrow ( $m, $i, '培训工程师要求', $objPHPExcel );
		}
		//数据输出
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
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
			}
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		}
		/*************************************保修条款信息*****************************************/
		//设置表头及样式 设置 -- 保修条款清单
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		for($m = 0; $m < 12; $m ++) {
			model_contract_common_orderExcelUtil::tableThead ( $m, $i, '保修条款', $objPHPExcel );
			$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getBorders ()->getBottom ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
		}
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . ++ $i );
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( 0, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $i - 1, iconv ( "GBK", "utf-8", $dataArr ['warrantyClause'] ) );

		/*************************************售后要求信息*****************************************/
		//设置表头及样式 设置 -- 售后要求清单
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		for($m = 0; $m < 12; $m ++) {
			model_contract_common_orderExcelUtil::tableThead ( $m, $i, '售后要求', $objPHPExcel );
			$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getBorders ()->getBottom ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
		}
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . ++ $i );
		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( 0, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $i - 1, iconv ( "GBK", "utf-8", $dataArr ['afterService'] ) );

		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
//		//到浏览器
		ob_end_clean (); //解决输出到浏览器出现乱码的问题
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "《" . $dataArr ['orderName'] . "》合同.xls" . '"' );
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
	 * 导入EXCEL数据到系统中
	 * @param  $file
	 * @param  $filetempname
	 */
	function readExcelData($file, $filetempname) {
		//自己设置的上传文件存放路径
		$filePath = 'upfile/';
		$filename = explode ( ".", $file ); //把上传的文件名以“.”好为准做一个数组。
		$time = date ( "y-m-d-H-i-s" ); //去当前上传的时间
		$filename [0] = $time; //取文件名替换
		$name = implode ( ".", $filename ); //上传后的文件名
		$uploadfile = $filePath . $name; //上传后的文件名地址


		//move_uploaded_file() 函数将上传的文件移动到新位置。若成功，则返回 true，否则返回 false。
		$result = move_uploaded_file ( $filetempname, $uploadfile ); //假如上传到当前目录下
		if ($result) { //如果上传文件成功，就执行导入excel操作
			$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
			$objPHPExcel = $objReader->load ( $uploadfile ); //   $objPHPExcel = $objReader->load($uploadfile);
			$sheet = $objPHPExcel->getSheet ( 0 );
			$highestRow = $sheet->getHighestRow (); // 取得总行数
			$highestColumn = $sheet->getHighestColumn (); // 取得总列数
			$dataArr = array (); //读取结果


			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );

			//循环读取excel文件,读取一条,存取到数组一条
			for($j = 2; $j <= $highestRow; $j ++) {
				$str = "";
				for($k = 0; $k <= $highestColumnIndex; $k ++) {
					$str .= iconv ( 'UTF-8', 'GBK', $sheet->getCellByColumnAndRow ( $k, $j )->getValue () ); //读取单元格
					if ($k != $highestColumnIndex) {
						$str .= '\\';
					}
				}

				$rowData = explode ( "\\", $str );
				array_push ( $dataArr, $rowData );
			}

			unlink ( $uploadfile ); //删除上传的excel文件
			$msg = "读取成功！";
		} else {
			$msg = "读取失败！";
		}

		return $dataArr;
	}

}
?>
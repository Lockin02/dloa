<?php

/**
 * @description: 实现数据导出到Excel的功能
 * @date 2010-10-20 下午07:18:35
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
	 * 采购类型
	 * 通过value查找状态
	 */
	function purchTypeToVal($purchVal) {
		$returnVal = false;

		 $purchaseType = array (
			0 => array (
				'purchCName' => '销售采购',
				'purchKey' => 'contract_sales',
			),
			1 => array (
				'purchCName' => '补库采购',
				'purchKey' => 'stock'
			),
			2 => array (
				'purchCName' => '研发采购',
				'purchKey' => 'rdproject'
			),
			3 => array (
				'purchCName' => '资产采购',
				'purchKey' => 'assets'
			),
			4 => array (
				'purchCName' => '订单采购',
				'purchKey' => 'order'
			),
			5 => array (
				'purchCName' => '合同采购',
				'purchKey' => 'contract_sales'
			),
			6 => array (
				'purchCName' => '生产采购',
				'purchKey' => 'produce'
			),
			7=> array (
				'purchCName' => '销售合同采购',
				'purchKey' => 'oa_sale_order'
			),
			8 => array (
				'purchCName' => '租赁合同采购',
				'purchKey' => 'oa_sale_lease'
			),
			9 => array (
				'purchCName' => '服务合同采购',
				'purchKey' => 'oa_sale_service'
			),
			10 => array (
				'purchCName' => '研发合同采购',
				'purchKey' => 'oa_sale_rdproject'
			),
			11 => array (
				'purchCName' => '借试用采购',
				'purchKey' => 'oa_borrow_borrow'
			),
			12 => array (
				'purchCName' => '赠送采购',
				'purchKey' => 'oa_present_present'
			),
			13=> array (
				'purchCName' => '销售合同采购',
				'purchKey' => 'HTLX-XSHT'
			),
			14 => array (
				'purchCName' => '租赁合同采购',
				'purchKey' => 'HTLX-ZLHT'
			),
			15 => array (
				'purchCName' => '服务合同采购',
				'purchKey' => 'HTLX-FWHT'
			),
			16 => array (
				'purchCName' => '研发合同采购',
				'purchKey' => 'HTLX-YFHT'
			)
		);
		foreach ( $purchaseType as $key => $val ) {
			if ($val ['purchKey'] == $purchVal) {
				$returnVal = $val ['purchCName'];
			}
		}
		//TODO:添加异常操作
		return $returnVal;
	}

	//模块内容居中样式
	function toCecter($i, $objPHPExcel) {
		for($temp = 0; $temp < 12; $temp ++) {
			$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $temp, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		}
	}

	/**
	 * $cellValue为传入的二维数组
	 */
	public static function export2ExcelUtil($rowdatas) {
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		//		//创建一个Excel工作流
		//		$objPhpExcelFile = new PHPExcel();


		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load ( "upfile/采购任务书导出模板.xls" ); //读取模板
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPhpExcelFile );

		//设置保存的文件名称及路径
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;

		//以下是对工作表基本参数的设置
		//设置当前工作表的名称
		$objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '信息列表' ) );
		//设置表头及样式 设置
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
				$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
			}
		}

		//到浏览器
		ob_end_clean (); //解决输出到浏览器出现乱码的问题
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "采购申请列表导出信息.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}

	/**
	 * $cellValue为传入的二维数组
	 */
	public static function exportAging_e($rowdatas) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/采购管理时效表.xls" ); //读取模板
		$excelActiveSheet = $objPHPExcel->getActiveSheet ();
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
		$i = 1;

		//星期
		$week = array ('星期一', '星期二', '星期三', '星期四', '星期五', '星期六', '星期天' );

		//审批结果
		$exaResult = array ('ok' => '通过', 'no' => '不通过' );

		//采购类型
		$purchTypeArr = array ('contract_sales' => '合同采购', 'contract_sales_equ' => '合同采购', 'stock' => '补库采购', 'assets' => '资产采购', 'rdproject' => '研发采购', 'order' => '订单采购', 'produce' => '生产采购', 'oa_sale_order' => '销售合同采购', 'oa_sale_lease' => '租赁合同采购', 'oa_sale_service' => '服务合同采购', 'oa_sale_rdproject' => '研发合同采购', 'oa_borrow_borrow' => '补库采购', 'oa_present_present' => '补库采购', 'oa_asset_purchase_apply' => '资产采购' );
		//		echo "<pre>";
		//		print_r($rowdatas);
		//		return false;
		foreach ( $rowdatas as $key => $val ) { //下达采购申请
			//人员花费天数数组
			$useDayArr = array();
			//下达采购申请
			++ $i;
			$objPHPExcel->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( 0, $i, iconv ( "gb2312", "utf-8", $val ['planCode'] ), PHPExcel_Cell_DataType::TYPE_STRING );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $i, iconv ( "gb2312", "utf-8", '综合经理' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $i, iconv ( "gb2312", "utf-8", $val ['planCreateBy'] ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $i, iconv ( "gb2312", "utf-8", '下达采购申请' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $i, iconv ( "gb2312", "utf-8", $val ['planSendDate'] . ' ' . $week [$val ['planSendDay']] ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $i, iconv ( "gb2312", "utf-8", $val ['planMonth'] * 1 . '月' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, $i, iconv ( "gb2312", "utf-8", $purchTypeArr [$val ['purchType']] ) );
			$objPHPExcel->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( 8, $i, iconv ( "gb2312", "utf-8", $val ['sourceNumb'] ), PHPExcel_Cell_DataType::TYPE_STRING );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, $i, iconv ( "gb2312", "utf-8", $val ['customerName'] ) );

			//接收采购申请
			++ $i;
			$objPHPExcel->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( 0, $i, iconv ( "gb2312", "utf-8", $val ['planCode'] ), PHPExcel_Cell_DataType::TYPE_STRING );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $i, iconv ( "gb2312", "utf-8", '采购经理' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $i, iconv ( "gb2312", "utf-8", $val ['taskCreateBy'] ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $i, iconv ( "gb2312", "utf-8", '接收采购申请' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $i, iconv ( "gb2312", "utf-8", $val ['planSendDate'] . ' ' . $week [$val ['planSendDay']] ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i, iconv ( "gb2312", "utf-8", 0 .'天' ) );

			if (empty ( $val ['taskSendDate'] )) {
				//计算相差日期
//				$date1 = strtotime ( $val ['planSendDate'] );
//				$date2 = strtotime ( $val ['planSendDate'] );
//				$days = ($date2 - $date1) / 86400;
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i - 1, iconv ( "gb2312", "utf-8", '总天数：0天' ) );

				//设置人员耗时
				$k = count($useDayArr);
				foreach($useDayArr as $key => $val){
					$k -- ;
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, $i - 1 + $k , iconv ( "gb2312", "utf-8",  $key.'：' .$val . '天' ) );
				}

				++ $i;
				continue;
			}
			//分配采购任务
			++ $i;
			$objPHPExcel->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( 0, $i, iconv ( "gb2312", "utf-8", $val ['planCode'] ), PHPExcel_Cell_DataType::TYPE_STRING );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $i, iconv ( "gb2312", "utf-8", '采购经理' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $i, iconv ( "gb2312", "utf-8", $val ['taskCreateBy'] ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $i, iconv ( "gb2312", "utf-8", '分配采购任务' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $i, iconv ( "gb2312", "utf-8", $val ['taskSendDate'] . ' ' . $week [$val ['taskSendDay']] ) );

			//计算单步骤日期
			$date1 = strtotime ( $val ['planSendDate'] );
			$date2 = strtotime ( $val ['taskSendDate'] );
			$stepDay = ($date2 - $date1) / 86400;
			//设置人员花费天数
			$useDayArr[$val ['taskCreateBy']] = $stepDay;

			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i, iconv ( "gb2312", "utf-8", $stepDay .'天' ) );

			if (empty ( $val ['taskEndDate'] )) {
				//计算相差日期
//				$date1 = strtotime ( $val ['planSendDate'] );
//				$date2 = strtotime ( $val ['taskSendDate'] );
//				$days = ($date2 - $date1) / 86400;
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i - 2, iconv ( "gb2312", "utf-8",  '总天数：' .$stepDay . '天' ) );

				//设置人员耗时
				$k = count($useDayArr);
				foreach($useDayArr as $key => $val){
					$k -- ;
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, $i - 2 + $k , iconv ( "gb2312", "utf-8",  $key.'：' .$val . '天' ) );
				}

				++ $i;
				continue;
			}
			//接收采购任务
			++ $i;
			$objPHPExcel->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( 0, $i, iconv ( "gb2312", "utf-8", $val ['taskNo'] ), PHPExcel_Cell_DataType::TYPE_STRING );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $i, iconv ( "gb2312", "utf-8", '采购员' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $i, iconv ( "gb2312", "utf-8", $val ['taskSendName'] ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $i, iconv ( "gb2312", "utf-8", '接收采购任务' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $i, iconv ( "gb2312", "utf-8", $val ['taskEndDate'] . ' ' . $week [$val ['taskEndDay']] ) );

			//计算单步骤日期
			$date1 = strtotime ( $val ['taskSendDate'] );
			$date2 = strtotime ( $val ['taskEndDate'] );
			$stepDay = ($date2 - $date1) / 86400;
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i, iconv ( "gb2312", "utf-8", $stepDay .'天' ) );
			//设置人员花费天数
			if(isset($useDayArr[$val ['taskSendName']])){
				$useDayArr[$val ['taskSendName']] = $useDayArr[$val ['taskSendName']] + $stepDay;
			}else{
				$useDayArr[$val ['taskSendName']] = $stepDay;
			}

			if (empty ( $val ['inquiryBeginDate'] )) {
				//计算相差日期
				$date1 = strtotime ( $val ['planSendDate'] );
				$date2 = strtotime ( $val ['taskEndDate'] );
				$days = ($date2 - $date1) / 86400;
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i - 3, iconv ( "gb2312", "utf-8", '总天数：' . $days . '天' ) );

				//设置人员耗时
				$k = count($useDayArr);
				foreach($useDayArr as $key => $val){
					$k -- ;
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, $i - 3 + $k , iconv ( "gb2312", "utf-8",  $key.'：' .$val . '天' ) );
				}

				++ $i;
				continue;
			}
			//提出询价审批
			++ $i;
			$objPHPExcel->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( 0, $i, iconv ( "gb2312", "utf-8", $val ['inquiryNo'] ), PHPExcel_Cell_DataType::TYPE_STRING );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $i, iconv ( "gb2312", "utf-8", '采购员' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $i, iconv ( "gb2312", "utf-8", $val ['inquiryCreateBy'] ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $i, iconv ( "gb2312", "utf-8", '提出询价审批' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $i, iconv ( "gb2312", "utf-8", $val ['inquiryBeginDate'] . ' ' . $week [$val ['inquiryBeginDay']] ) );

			//计算单步骤日期
			$date1 = strtotime ( $val ['taskEndDate'] );
			$date2 = strtotime ( $val ['inquiryBeginDate'] );
			$stepDay = ($date2 - $date1) / 86400;
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i, iconv ( "gb2312", "utf-8", $stepDay .'天' ) );
			//设置人员花费天数
			if(isset($useDayArr[$val ['inquiryCreateBy']])){
				$useDayArr[$val ['inquiryCreateBy']] = $useDayArr[$val ['inquiryCreateBy']] + $stepDay;
			}else{
				$useDayArr[$val ['inquiryCreateBy']] = $stepDay;
			}

			if (empty ( $val ['inquiryEndDate'] )) {
				//计算相差日期
				$date1 = strtotime ( $val ['planSendDate'] );
				$date2 = strtotime ( $val ['inquiryBeginDate'] );
				$days = ($date2 - $date1) / 86400;
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i - 4, iconv ( "gb2312", "utf-8",  '总天数：' .$days . '天' ) );

				//设置人员耗时
				$k = count($useDayArr);
				foreach($useDayArr as $key => $val){
					$k -- ;
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, $i - 4 + $k , iconv ( "gb2312", "utf-8",  $key.'：' .$val . '天' ) );
				}

				++ $i;
				continue;
			}
			//审批采购询价
			++ $i;
			$objPHPExcel->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( 0, $i, iconv ( "gb2312", "utf-8", $val ['inquiryNo'] ), PHPExcel_Cell_DataType::TYPE_STRING );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $i, iconv ( "gb2312", "utf-8", '采购经理' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $i, iconv ( "gb2312", "utf-8", $val ['inquiryExaBy'] ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $i, iconv ( "gb2312", "utf-8", '审批采购询价' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $i, iconv ( "gb2312", "utf-8", $val ['inquiryEndDate'] . ' ' . $week [$val ['inquiryEndDay']] ) );

			//计算单步骤日期
			$date1 = strtotime ( $val ['inquiryBeginDate'] );
			$date2 = strtotime ( $val ['inquiryEndDate'] );
			$stepDay = ($date2 - $date1) / 86400;
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i, iconv ( "gb2312", "utf-8", $stepDay .'天' ) );
			//设置人员花费天数
			if(isset($useDayArr[$val ['inquiryExaBy']])){
				$useDayArr[$val ['inquiryExaBy']] = $useDayArr[$val ['inquiryExaBy']] + $stepDay;
			}else{
				$useDayArr[$val ['inquiryExaBy']] = $stepDay;
			}

			if (empty ( $val ['applyBeginDate'] )) {
				//计算相差日期
				$date1 = strtotime ( $val ['planSendDate'] );
				$date2 = strtotime ( $val ['inquiryEndDate'] );
				$days = ($date2 - $date1) / 86400;
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i - 5, iconv ( "gb2312", "utf-8",  '总天数：' .$days . '天' ) );

				//设置人员耗时
				$k = count($useDayArr);
				foreach($useDayArr as $key => $val){
					$k -- ;
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, $i - 5 + $k , iconv ( "gb2312", "utf-8",  $key.'：' .$val . '天' ) );
				}

				++ $i;
				continue;
			}
			//采购订单立项
			++ $i;
			$objPHPExcel->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( 0, $i, iconv ( "gb2312", "utf-8", $val ['applyNo'] ), PHPExcel_Cell_DataType::TYPE_STRING );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $i, iconv ( "gb2312", "utf-8", '采购员' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $i, iconv ( "gb2312", "utf-8", $val ['applyCreateBy'] ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $i, iconv ( "gb2312", "utf-8", '提出采购订单' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $i, iconv ( "gb2312", "utf-8", $val ['applyBeginDate'] . ' ' . $week [$val ['applyBeginDay']] ) );

			//计算单步骤日期
			$date1 = strtotime ( $val ['inquiryEndDate'] );
			$date2 = strtotime ( $val ['applyBeginDate'] );
			$stepDay = ($date2 - $date1) / 86400;
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i, iconv ( "gb2312", "utf-8", $stepDay .'天' ) );
			//设置人员花费天数
			if(isset($useDayArr[$val ['applyCreateBy']])){
				$useDayArr[$val ['applyCreateBy']] = $useDayArr[$val ['applyCreateBy']] + $stepDay;
			}else{
				$useDayArr[$val ['applyCreateBy']] = $stepDay;
			}

			if (empty ( $val ['applyExaBy'] )) {
				//计算相差日期
				$date1 = strtotime ( $val ['planSendDate'] );
				$date2 = strtotime ( $val ['applyBeginDate'] );
				$days = ($date2 - $date1) / 86400;
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i - 6, iconv ( "gb2312", "utf-8",  '总天数：' .$days . '天' ) );

				//设置人员耗时
				$k = count($useDayArr);
				foreach($useDayArr as $key => $val){
					$k -- ;
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, $i - 6 + $k , iconv ( "gb2312", "utf-8",  $key.'：' .$val . '天' ) );
				}

				++ $i;
				continue;
			}
			//采购订单审批
			++ $i;
			$objPHPExcel->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( 0, $i, iconv ( "gb2312", "utf-8", $val ['applyNo'] ), PHPExcel_Cell_DataType::TYPE_STRING );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $i, iconv ( "gb2312", "utf-8", '采购经理' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $i, iconv ( "gb2312", "utf-8", $val ['applyExaBy'] ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $i, iconv ( "gb2312", "utf-8", '采购订单审批' ) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $i, iconv ( "gb2312", "utf-8", $val ['applyEndDate'] . ' ' . $week [$val ['applyEndDay']] ) );

			//计算单步骤日期
			$date1 = strtotime ( $val ['applyBeginDate'] );
			$date2 = strtotime ( $val ['applyEndDate'] );
			$stepDay = ($date2 - $date1) / 86400;
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i, iconv ( "gb2312", "utf-8", $stepDay .'天' ) );
			//设置人员花费天数
			if(isset($useDayArr[$val ['applyExaBy']])){
				$useDayArr[$val ['applyExaBy']] = $useDayArr[$val ['applyExaBy']] + $stepDay;
			}else{
				$useDayArr[$val ['applyExaBy']] = $stepDay;
			}

			//计算相差日期
			$date1 = strtotime ( $val ['planSendDate'] );
			$date2 = strtotime ( $val ['applyEndDate'] );
			$days = ($date2 - $date1) / 86400;
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $i - 7, iconv ( "gb2312", "utf-8",  '总天数：' .$days . '天' ) );

			//设置人员耗时
			$k = count($useDayArr);
			foreach($useDayArr as $key => $val){
				$k -- ;
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, $i - 7 + $k , iconv ( "gb2312", "utf-8",  $key.'：' .$val . '天' ) );
			}

			++ $i;
		}
//		//到浏览器
		ob_end_clean (); //解决输出到浏览器出现乱码的问题
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "采购管理时效表.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}

	/**
	 * 导出同批次物料信息
	 *
	 */
	function exportProduceEqu_e($dataArr, $batchNumb) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/按批次号导出模板.xls" ); //读取模板
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
		//到浏览器
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "采购申请-$batchNumb.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );

	}

	/**
	 * 导出采购申请物料信息
	 *
	 */
	function exportPlanEqu_e($dataArr) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/采购申请导出模板.xls" ); //读取模板
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
		//到浏览器
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "采购申请物料信息.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );

	}


	/**
	 * 导出已关闭采购任务物料信息
	 *
	 */
	function exportTaskCloseEqu_e($dataArr) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/已关闭采购任务物料汇总.xls" ); //读取模板
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
		//到浏览器
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "已关闭采购任务物料汇总.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );

	}

	/**
	 * 导出采购任务物料执行信息
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
		$objPHPExcel = $objReader->load ( "upfile/采购任务执行情况汇总.xls" ); //读取模板
		$excelActiveSheet = $objPHPExcel->getActiveSheet ();
		$nowRow = 2; //采购任务序数


		for($n = 0; $n < count ( $dataArr ); $n ++) {
			if (is_array ( $dataArr [$n] )) {//获取订单物料信息
				$orderEquRows = $orderEquDao->getProgressEqusByTequId ( $dataArr [$n] ['id'] );
			}
			$taskState=$taskDao->statusDao->statusKtoC( $dataArr [$n]['state']);
			$seNum = 0; //采购订单条数
			if (count ( $orderEquRows ) > 1) {
				for($j = 1; $j < count ( $orderEquRows ) + 1; $j ++) {
					if (count ( $orderEquRows ) > 1) {
						for($j = 1; $j < count ( $orderEquRows ); $j ++) {
							$arrivalEquRows=$arrivalEquDao->getItemByContractEquId_d( $orderEquRows [$j]['id']);
							$payed=$payDao->getPayedMoney_d( $orderEquRows [$j]['id'],$objType = 'YFRK-01');
							$stateName=$orderDao->stateToVal ( $orderEquRows [$j] ['state']);
							if($payed==0){
								$payStr='未付';
							}else if($payed== $orderEquRows [$j]['moneyAll']){
								$payStr='已付';
							}else{
								$payStr='部分';
							}
							$arrivalNum=0;
							if(is_array($arrivalEquRows)){   //获取某物料的收料情况
								foreach($arrivalEquRows as $arrKey=>$arrVal){
									$arrivalNum=$arrivalNum+$arrVal['arrivalNum'];
								}
							}
							$excelActiveSheet->setCellValueByColumnAndRow ( 8, $nowRow + $seNum + $j - 1, iconv ( "GBK", "utf-8", $orderEquRows [$j] ['amountAll'] ) ); //订单数量
							$excelActiveSheet->setCellValueByColumnAndRow ( 9, $nowRow + $seNum + $j - 1, iconv ( "GBK", "utf-8", $orderEquRows [$j] ['ExaStatus'] ) ); //订单审批状态
							$excelActiveSheet->setCellValueByColumnAndRow (10, $nowRow + $seNum + $j - 1, iconv ( "GBK", "utf-8", $stateName ) ); //订单状态
							$excelActiveSheet->setCellValueByColumnAndRow ( 11, $nowRow + $seNum + $j - 1, iconv ( "GBK", "utf-8", $arrivalNum ) ); //收料数量
							$excelActiveSheet->setCellValueByColumnAndRow ( 12, $nowRow + $seNum + $j - 1, iconv ( "GBK", "utf-8", $orderEquRows [$j] ['amountIssued'] ) ); //入库数量
							$excelActiveSheet->setCellValueByColumnAndRow ( 13, $nowRow + $seNum + $j - 1, iconv ( "GBK", "utf-8", $payStr ) ); //付款情况
						}
						$seNum += count ( $orderEquRows );
					} else {
						$seNum += 1;
					}
					$arrivalEquRows1=$arrivalEquDao->getItemByContractEquId_d( $orderEquRows [0]['id']);
					$payed1=$payDao->getPayedMoney_d( $orderEquRows [0]['id'],$objType = 'YFRK-01');
					$stateName1=$orderDao->stateToVal ( $orderEquRows [0] ['state']);
					if($payed1==0){
						$payStr1='未付';
					}else if($payed1== $orderEquRows [0]['moneyAll']){
						$payStr1='已付';
					}else{
						$payStr1='部分';
					}
					$arrivalNum1=0;
					if(is_array($arrivalEquRows1)){   //获取某物料的收料情况
						foreach($arrivalEquRows1 as $arrKey=>$arrVal){
							$arrivalNum1=$arrivalNum1+$arrVal['arrivalNum'];
						}
					}

					//合了采购询价列后 的第二行信息
					$excelActiveSheet->setCellValueByColumnAndRow ( 8, $nowRow + $seNum - 1, iconv ( "GBK", "utf-8", $orderEquRows [0] ['amountAll'] ) ); //订单数量
					$excelActiveSheet->setCellValueByColumnAndRow ( 9, $nowRow + $seNum - 1, iconv ( "GBK", "utf-8", $orderEquRows [0] ['ExaStatus'] ) ); //订单审批状态
					$excelActiveSheet->setCellValueByColumnAndRow ( 10, $nowRow + $seNum - 1, iconv ( "GBK", "utf-8", $stateName1) ); //订单状态
					$excelActiveSheet->setCellValueByColumnAndRow ( 11, $nowRow + $seNum - 1, iconv ( "GBK", "utf-8", $arrivalNum1 ) ); //收料数量
					$excelActiveSheet->setCellValueByColumnAndRow ( 12, $nowRow + $seNum - 1, iconv ( "GBK", "utf-8", $orderEquRows [0] ['amountIssued'] ) ); //入库数量
					$excelActiveSheet->setCellValueByColumnAndRow ( 13, $nowRow + $seNum - 1, iconv ( "GBK", "utf-8", $payStr1 ) ); //付款情况
				}
			} else {
					if(count( $orderEquRows )>0){
						$arrivalEquRows=$arrivalEquDao->getItemByContractEquId_d( $orderEquRows [0]['id']);
						$payed=$payDao->getPayedMoney_d( $orderEquRows [0]['id'],$objType = 'YFRK-01');
						$stateName=$orderDao->stateToVal ( $orderEquRows [0] ['state']);
						if($payed==0){
							$payStr='未付';
						}else if($payed== $orderEquRows [0]['moneyAll']){
							$payStr='已付';
						}else{
							$payStr='部分';
						}
						$arrivalNum=0;
						if(is_array($arrivalEquRows)){   //获取某物料的收料情况
							foreach($arrivalEquRows as $arrKey=>$arrVal){
								$arrivalNum=$arrivalNum+$arrVal['arrivalNum'];
							}
						}
						$excelActiveSheet->setCellValueByColumnAndRow ( 8, $nowRow + $seNum , iconv ( "GBK", "utf-8", $orderEquRows [0] ['amountAll'] ) ); //订单数量
						$excelActiveSheet->setCellValueByColumnAndRow ( 9, $nowRow + $seNum , iconv ( "GBK", "utf-8", $orderEquRows [0] ['ExaStatus'] ) ); //订单审批状态
						$excelActiveSheet->setCellValueByColumnAndRow ( 10, $nowRow + $seNum , iconv ( "GBK", "utf-8", $stateName ) ); //订单状态
						$excelActiveSheet->setCellValueByColumnAndRow ( 11, $nowRow + $seNum , iconv ( "GBK", "utf-8", $arrivalNum ) ); //收料数量
						$excelActiveSheet->setCellValueByColumnAndRow ( 12, $nowRow + $seNum , iconv ( "GBK", "utf-8", $orderEquRows [0] ['amountIssued'] ) ); //入库数量
						$excelActiveSheet->setCellValueByColumnAndRow ( 13, $nowRow + $seNum , iconv ( "GBK", "utf-8", $payStr) ); //付款情况

					}
					$seNum += 1;
			}

			//合并采购任务
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

			//第一行信息
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
		//到浏览器
		ob_end_clean ();
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "采购任务执行情况.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );

	}
}
?>
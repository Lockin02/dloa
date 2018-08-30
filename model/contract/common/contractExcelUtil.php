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

	//模块名样式
	function tableThead($column, $row, $content, $objPHPExcel) {
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setSize(16);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, iconv("gb2312", "utf-8", $content));
	}
	//模块列样式
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
		//格式化数字为字符串，避免导致数字默认右对齐单元格
		//		$objPHPExcel->getActiveSheet ()->getStyle('C11')->getNumberFormat()->setFormatCode("@");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->getStartColor()->setARGB("00ECF8FB");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, iconv("gb2312", "utf-8", $content));
	}
	//模块列样式
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
	//模块内容居中样式
	function toCecter($i, $objPHPExcel) {
		for($temp = 0; $temp < 12; $temp ++) {
			$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $temp, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		}
	}

	/**
	 * 其他合同导出
	 * 2012-06-07
	 */
	public static function otherContractOut_e($rowdatas) {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/saleOther_ExportTmp.xls" ); //读取模板
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );

		//签收状态
		$signArr = array('未签收','已签收');

		//合同状态
		$statusArr = array("未提交","审批中","执行中","已关闭","变更中");

		//盖章状态
		$stampArr = array("否","是");

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

            // 保证金新增字段
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(29,$insertRow,iconv("GBK","utf-8",$val['payForBusinessName']));
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(30,$insertRow,iconv("GBK","utf-8",$val['chanceCode']));
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(31,$insertRow,iconv("GBK","utf-8",$val['prefBidDate']));
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(32,$insertRow,iconv("GBK","utf-8",$val['contractCode']));
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(33,$insertRow,iconv("GBK","utf-8",$val['projectPrefEndDate']));
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(34,$insertRow,iconv("GBK","utf-8",$val['delayPayDays']));

            $isBankbackLetter = "";
            if($val['isBankbackLetter'] == 1){
                $isBankbackLetter = "是";
            }else if($val['payForBusiness'] == "FKYWLX-03" || $val['payForBusiness'] == "FKYWLX-04"){
                $isBankbackLetter = "否";
            }
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(35,$insertRow,iconv("GBK","utf-8",$isBankbackLetter));

            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(36,$insertRow,iconv("GBK","utf-8",$val['backLetterEndDate']));
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(37,$insertRow,iconv("GBK","utf-8",$val['prefPayDate']));
		}
		//到浏览器
        ob_end_clean();//解决输出到浏览器出现乱码的问题
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "其他合同导出.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}
	
	function outsourcingContractOut_e($rowdatas){
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/外包合同导出模版.xls" ); //读取模板
		$excelActiveSheet=$objPHPExcel->getActiveSheet ();
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
		
		foreach( $rowdatas as $key => $val){
			$insertRow = 2+$key;
			
			//状态
			$statusArr = array("未提交","审批中","执行中","已关闭","变更中");
			
			//签收状态
			$signArr = array('未签收','已签收');
			
			//盖章状态
			$stampArr = array("否","是");
			
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
		//到浏览器
		ob_end_clean();//解决输出到浏览器出现乱码的问题
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "外包合同导出.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}



	//读取模板并导出
	public static function exporTemplate($dataArr, $rowdatas, $imageName) {
//		echo 11111111;
//		echo "<pre>";
//		print_R($dataArr);
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/contractExport.xls" ); //读取模板
		$excelActiveSheet = $objPHPExcel->getActiveSheet ();
		//设置保存的文件名称及路径
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;
		$objPhpExcelFile = new PHPExcel ();

		//设置表头及样式 设置
		for($m = 0; $m < 12; $m ++) {
			model_contract_common_contractExcelUtil::tableThead ( $m, 1, '合同信息', $objPHPExcel );
		}
		foreach ( $dataArr as $key => $val ) {
			if ($val === null) {
				$dataArr [$key] [$val] = '';
			}
		}
		//获取从表数据
		//付款条件
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
		//合同联系人
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
		//产品清单
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
		//收开计划
		$tempSequence = 1;
		foreach ( $dataArr ['financialplan'] as $sequence => $equ ) {
			$financialplan [$sequence] ['sequence'] = $tempSequence;
			$financialplan [$sequence] ['planDate'] = $dataArr ['financialplan'] [$sequence] ['planDate'];
			$financialplan [$sequence] ['invoiceMoney'] = $dataArr ['financialplan'] [$sequence] ['invoiceMoney'];
			$financialplan [$sequence] ['incomeMoney'] = $dataArr ['financialplan'] [$sequence] ['incomeMoney'];
			$financialplan [$sequence] ['remark'] = $dataArr ['financialplan'] [$sequence] ['remark'];
			$tempSequence ++;
		}
		//物料清单
		$tempSequence = 1;
		foreach ( $dataArr ['equ'] as $sequence => $equ ) {
			$equArr [$sequence] ['sequence'] = $tempSequence;
			$equArr [$sequence] ['productCode'] = $dataArr ['equ'] [$sequence] ['productCode'];
			$equArr [$sequence] ['productName'] = $dataArr ['equ'] [$sequence] ['productName'];
			$equArr [$sequence] ['number'] = $dataArr ['equ'] [$sequence] ['productModel'];
			$equArr [$sequence] ['warrantyPeriod'] = $dataArr ['equ'] [$sequence] ['number'];
			$tempSequence ++;
		}
		//物料清单
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
//		//开票计划
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
//		//收款计划
//		$tempSequence = 1;
//		foreach ( $dataArr ['income'] as $sequence => $equ ) {
//			$income [$sequence] ['receiptplan'] = $tempSequence;
//			$income [$sequence] ['money'] = $dataArr ['income'] [$sequence] ['money'];
//			$income [$sequence] ['payDT'] = $dataArr ['income'] [$sequence] ['payDT'];
//			$income [$sequence] ['pType'] = $dataArr ['income'] [$sequence] ['pType'];
//			$income [$sequence] ['collectionTerms'] = $dataArr ['income'] [$sequence] ['collectionTerms'];
//			$tempSequence ++;
//		}
//		//培训计划
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
		/*************************************主表信息*****************************************/

		for($j = 3; $j <= 17; $j ++) { //读取模板字段并替换
			for($k = 'A'; $k <= 'K'; $k ++) {
				$ename = iconv ( 'utf-8', 'gb2312', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue () ); //读取单元格
				if (isset ( $dataArr [$ename] )) {
					$pvalue = $dataArr [$ename];
					$objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->setValue ( iconv ( "gb2312", "utf-8", $pvalue ) );
				}
			}
		}
		/*************************************付款条件*****************************************/
		$i = 18;
		//设置表头及样式 设置 -- 联系人
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i++ );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
		model_contract_common_contractExcelUtil::tableThead ( 0, $i++, '付款条件', $objPHPExcel );

		$objPHPExcel->getActiveSheet ()->mergeCells ( 'B' . $i . ':C' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'D' . $i . ':E' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'F' . $i . ':G' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'H' . $i . ':I' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'J' . $i . ':L' . $i );

		//设置表头及样式 设置 -- 联系人 序号
		model_contract_common_contractExcelUtil::tableTrow ( 0, $i, '序号', $objPHPExcel );
		//设置表头及样式 设置 -- 联系人 联系人名称
		for($m = 1; $m < 3; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '付款条件', $objPHPExcel );
		}
		//设置表头及样式 设置 -- 联系人 电话
		for($m = 3; $m < 5; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '付款百分比（%）', $objPHPExcel );
		}
		//设置表头及样式 设置 -- 联系人qq
		for($m = 5; $m < 7; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '计划付款金额', $objPHPExcel );
		}
		//设置表头及样式 设置 -- 联系人 邮件
		for($m = 7; $m < 9; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '计划付款日期', $objPHPExcel );
		}
		//设置表头及样式 设置 -- 联系人 联系人名称
		for($m = 9; $m < 12; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '备注', $objPHPExcel );
		}
		//数据输出
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
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
			}
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		}




		/*************************************合同联系人信息*****************************************/
		$i ++;
		//设置表头及样式 设置 -- 联系人
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i++ );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
		model_contract_common_contractExcelUtil::tableThead ( 0, $i++, '客户联系人', $objPHPExcel );

		$objPHPExcel->getActiveSheet ()->mergeCells ( 'B' . $i . ':C' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'D' . $i . ':E' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'F' . $i . ':G' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'H' . $i . ':I' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'J' . $i . ':L' . $i );

		//设置表头及样式 设置 -- 联系人 序号
		model_contract_common_contractExcelUtil::tableTrow ( 0, $i, '序号', $objPHPExcel );
		//设置表头及样式 设置 -- 联系人 联系人名称
		for($m = 1; $m < 3; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '客户联系人', $objPHPExcel );
		}
		//设置表头及样式 设置 -- 联系人 电话
		for($m = 3; $m < 5; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '电话', $objPHPExcel );
		}
		//设置表头及样式 设置 -- 联系人qq
		for($m = 5; $m < 7; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, 'QQ', $objPHPExcel );
		}
		//设置表头及样式 设置 -- 联系人 邮件
		for($m = 7; $m < 9; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '邮箱', $objPHPExcel );
		}
		//设置表头及样式 设置 -- 联系人 联系人名称
		for($m = 9; $m < 12; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '备注', $objPHPExcel );
		}
		//数据输出
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
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
			}
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		}
		/*************************************产品清单信息*****************************************/

		//设置表头及样式 设置 -- 产品清单
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		model_contract_common_contractExcelUtil::tableThead ( 0, $i, '产品清单', $objPHPExcel );

		//设置表头及样式 设置 -- 产品清单 列名
		$i ++;
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'B' . $i . ':D' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'E' . $i . ':I' . $i );
		model_contract_common_contractExcelUtil::tableTrow ( 0, $i, '序号', $objPHPExcel );
		for($m = 1; $m < 4; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '产品名称', $objPHPExcel );
		}
		for($m = 4; $m < 9; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '产品描述', $objPHPExcel );
		}
		$orderequTr = array ( '数量', '单价', '金额' );
		for($m = 9; $m < 12; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, $orderequTr [$m-9], $objPHPExcel );
		}
		//数据输出
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
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
			}
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		}
		
		/*************************************收开计划信息*****************************************/
		
		//设置表头及样式 设置 -- 收开计划
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		model_contract_common_contractExcelUtil::tableThead ( 0, $i, '收开计划', $objPHPExcel );
		
		//设置表头及样式 设置 -- 收开计划列名
		$i ++;
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'B' . $i . ':D' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'E' . $i . ':F' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'G' . $i . ':H' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'I' . $i . ':L' . $i );
		model_contract_common_contractExcelUtil::tableTrow ( 0, $i, '序号', $objPHPExcel );
		for($m = 1; $m < 4; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '日期', $objPHPExcel );
		}
		for($m = 4; $m < 6; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '开票金额', $objPHPExcel );
		}
		for($m = 6; $m < 8; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '收款金额', $objPHPExcel );
		}
		for($m = 8; $m < 12; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '备注', $objPHPExcel );
		}
		//数据输出
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
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
			}
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		}

		/*************************************借试用转销售物料清单信息*****************************************/

		//设置表头及样式 设置 -- 产品清单
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		model_contract_common_contractExcelUtil::tableThead ( 0, $i, '借用转销售物料', $objPHPExcel );

		//设置表头及样式 设置 -- 产品清单 列名
		$i ++;
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'B' . $i . ':E' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'F' . $i . ':J' . $i );
		model_contract_common_contractExcelUtil::tableTrow ( 0, $i, '序号', $objPHPExcel );
		for($m = 1; $m < 5; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '物料编号', $objPHPExcel );
		}
		for($m = 5; $m < 11; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '物料名称', $objPHPExcel );
		}
		$orderequTr = array (  '型号', '数量' );
		for($m = 10; $m < 12; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, $orderequTr [$m-10], $objPHPExcel );
		}
		//数据输出
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
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
			}
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		}

		/*************************************物料清单信息*****************************************/

		//设置表头及样式 设置 -- 产品清单
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		model_contract_common_contractExcelUtil::tableThead ( 0, $i, '合同发货清单', $objPHPExcel );

		//设置表头及样式 设置 -- 产品清单 列名
		$i ++;
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'B' . $i . ':E' . $i );
		$objPHPExcel->getActiveSheet ()->mergeCells ( 'F' . $i . ':J' . $i );
		model_contract_common_contractExcelUtil::tableTrow ( 0, $i, '序号', $objPHPExcel );
		for($m = 1; $m < 5; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '物料编号', $objPHPExcel );
		}
		for($m = 5; $m < 11; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '物料名称', $objPHPExcel );
		}
		$orderequTr = array (  '型号', '数量' );
		for($m = 10; $m < 12; $m ++) {
			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, $orderequTr [$m-10], $objPHPExcel );
		}
		//数据输出
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
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
			}
			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
		}
//		/*************************************开票计划信息*****************************************/
//		//设置表头及样式 设置 -- 开票计划
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++$i . ':L' . $i );
//		model_contract_common_contractExcelUtil::tableThead ( 0, $i, '开票计划', $objPHPExcel );
//		$i ++;
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'B' . $i . ':C' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'D' . $i . ':E' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'F' . $i . ':G' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'H' . $i . ':I' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'J' . $i . ':L' . $i );
//		//设置表头及样式 设置 -- 开票计划 列名
//		model_contract_common_contractExcelUtil::tableTrow ( 0, $i, '序号', $objPHPExcel );
//		for($m = 1; $m < 3; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '开票金额', $objPHPExcel );
//		}
//		for($m = 3; $m < 5; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '其中软件金额', $objPHPExcel );
//		}
//		for($m = 5; $m < 7; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '开票类型', $objPHPExcel );
//		}
//		for($m = 7; $m < 9; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '开票日期', $objPHPExcel );
//		}
//		for($m = 9; $m < 12; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '开票内容', $objPHPExcel );
//		}
//		//数据输出
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
//				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
//			}
//			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
//		}
//		/*************************************收款计划信息*****************************************/
//		//设置表头及样式 设置 -- 收款计划 列名
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
//		model_contract_common_contractExcelUtil::tableThead ( 0, $i, '收款计划', $objPHPExcel );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++$i . ':B' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'C' . $i . ':L' . $i );
//		for($m = 0; $m < 2; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '支付条款:', $objPHPExcel );
//
//		}
//		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $i, iconv ( "GBK", "utf-8", $dataArr ['paymentterm'] ) );
//
//		$i ++;
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'B' . $i . ':C' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'D' . $i . ':E' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'F' . $i . ':G' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'H' . $i . ':L' . $i );
//		model_contract_common_contractExcelUtil::tableTrow ( 0, $i, '序号', $objPHPExcel );
//		for($m = 1; $m < 3; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '收款金额', $objPHPExcel );
//		}
//		for($m = 3; $m < 5; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '收款日期', $objPHPExcel );
//		}
//		for($m = 5; $m < 7; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '收款方式', $objPHPExcel );
//		}
//		for($m = 7; $m < 12; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '收款条件', $objPHPExcel );
//		}
//		//数据输出
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
//				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
//			}
//			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
//		}
		/*************************************培训计划信息*****************************************/

//		//设置表头及样式 设置 -- 培训计划清单
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
//		model_contract_common_contractExcelUtil::tableThead ( 0, $i, '培训计划', $objPHPExcel );
//
//		//设置表头及样式 设置 --培训计划清单 列名
//		$orderequTr = array ('序号', '培训开始时间', '培训结束时间', '参与人数' );
//		$i ++;
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'E' . $i . ':F' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'G' . $i . ':I' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'J' . $i . ':L' . $i );
//		for($m = 0; $m < 4; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, $orderequTr [$m], $objPHPExcel );
//		}
//		for($m = 4; $m < 6; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '培训地点', $objPHPExcel );
//		}
//		for($m = 6; $m < 9; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '培训内容', $objPHPExcel );
//		}
//		for($m = 9; $m < 12; $m ++) {
//			model_contract_common_contractExcelUtil::tableTrow ( $m, $i, '培训工程师要求', $objPHPExcel );
//		}
//		//数据输出
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
//				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
//			}
//			$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
//		}
//		/*************************************保修条款信息*****************************************/
//		//设置表头及样式 设置 -- 保修条款清单
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
//		for($m = 0; $m < 12; $m ++) {
//			model_contract_common_contractExcelUtil::tableThead ( $m, $i, '保修条款', $objPHPExcel );
//			$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getBorders ()->getBottom ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
//		}
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . ++ $i );
//		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( 0, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
//		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $i - 1, iconv ( "GBK", "utf-8", $dataArr ['warrantyClause'] ) );
//
//		/*************************************售后要求信息*****************************************/
//		//设置表头及样式 设置 -- 售后要求清单
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $i . ':L' . $i );
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . $i );
//		for($m = 0; $m < 12; $m ++) {
//			model_contract_common_contractExcelUtil::tableThead ( $m, $i, '售后要求', $objPHPExcel );
//			$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getBorders ()->getBottom ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
//		}
//		$objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . ++ $i . ':L' . ++ $i );
//		$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( 0, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
//		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $i - 1, iconv ( "GBK", "utf-8", $dataArr ['afterService'] ) );

		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
//		//到浏览器
		ob_end_clean (); //解决输出到浏览器出现乱码的问题
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "《" . $dataArr ['contractCode'] . "》合同.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}

    /*
     * $cellValue为传入的二维数组
     */
	public static function export2ExcelUtil($thArr, $rowdatas) {
		//		$stime=microtime(true); //获取程序开始执行的时间
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
		//		//创建一个Excel工作流
		//		$objPhpExcelFile = new PHPExcel();

		$objReader = PHPExcel_IOFactory :: createReader('Excel2007'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/orderReportList07.xlsx"); //读取模板
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel2007($objPhpExcelFile);

		//以下是对工作表基本参数的设置
		//设置当前工作表的名称
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", '信息列表'));
		//设置表头及样式 设置
		//		$highestColumn = chr(ord('A') + count($thArr) - 1);
		//		$objPhpExcelFile->getActiveSheet()->mergeCells('A1:G1');
		//		for ($m = 0; $m < count($thArr); $m++) {
		//			model_contract_common_contractExcelUtil :: tableThead(0, 1, '列表信息', $objPhpExcelFile);
		//			model_contract_common_contractExcelUtil :: toCecter(1, $objPhpExcelFile);
		//		}
		//设置表头及样式 设置
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
				$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $i, iconv("gb2312", "utf-8", '暂无相关信息'));
			}
		}
		//到浏览器
		ob_end_clean(); //解决输出到浏览器出现乱码的问题
		header("Content-type: text/html; charset=UTF-8");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "列表信息.xlsx" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
		//$etime=microtime(true);//获取程序执行结束的时间
		//$total=$etime-$stime;   //计算差值
		//echo "<br />{$total} times";
	}


	/*
	 * 导出周报考核信息
	 */
	public static function excelTemp($thArr, $rowdatas) {ob_start();
//		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
//		$objPHPExcel = $objReader->load ( "upfile/orderReportList07(new).xlsx" ); //读取模板
//		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );


		$objReader = PHPExcel_IOFactory :: createReader('Excel2007'); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load("upfile/orderReportList07.xlsx"); //读取模板
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

//		$sheet = $objPHPExcel->getSheet ( 0 );
//		$highestRow = $sheet->getHighestRow (); // 取得总行数
//		$highestColumn = $sheet->getHighestColumn (); // 取得总列数
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
//				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($m, $i, iconv("gb2312", "utf-8", '暂无相关信息'));
//			}
//		}

		//到浏览器
		ob_end_clean(); //解决输出到浏览器出现乱码的问题
header("Content-type: text/html; charset=UTF-8");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "列表信息.xlsx" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}
	
	/**
	 * excel生成部分
	 * @param $thArr
	 * @param $data
	 * @param string $fileName
	 * @throws Exception
	 */
	public static function exportBasicExcelUtil($thArr, $data, $fileName = '列表信息') {
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
	
		$objReader = PHPExcel_IOFactory:: createReader('Excel5'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/orderReportList07.xls"); //读取模板
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);
		//设置自动行高
		$objPhpExcelFile->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
		//设置表头及样式 设置
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
					//自动换行
					$objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $row + $key)->getAlignment()->setWrapText(true);
					//解决显示为科学计数法的问题
					$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key,
							iconv("gb2312", "utf-8", $val[$field]), PHPExcel_Cell_DataType::TYPE_STRING);
					$m++;
				}
			}
		}
		//到浏览器
		ob_end_clean(); //解决输出到浏览器出现乱码的问题
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
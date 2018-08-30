<?php
class controller_purchase_report_purchasereport extends controller_base_action {

	function __construct() {
		$this->objName = "purchasereport";
		$this->objPath = "purchase_report";
		parent::__construct ();
	}

	/**
	 *
	 * 供应商采购金额统计报表
	 */
	function c_toSupSubPage() {
		if (isset ( $_GET ['thisYear'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = date ( 'm' ) * 1;

			//初始化数组
			$initArr = array ("thisYear" => $thisYear, "beginMonth" => 1, "endMonth" => $thisMonth, "purchTypeCN" => '', "suppId" => '', "suppName" => '' );
		}

		$this->assignFunc ( $initArr );
		$this->view ( "suppsub-detail" );
	}

	/**
	 *
	 * 供应商采购金额统计报表查询页面
	 */
	function c_toSupSubPageSearch() {
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "年</option>";
		}
		$this->assign ( 'yearStr', $yearStr );
		$this->assignFunc ( $_GET );
		$this->view ( "suppsub-search" );
	}

	/****************** S 采购金额汇总表_采购类型 *********************/
	/**
	 * 列表显示
	 * create by kuangzw
	 */
	function c_toPurchTypeSub() {
		if (isset ( $_GET ['thisYear'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = date ( 'm' ) * 1;

			//初始化数组
			$initArr = array ("thisYear" => $thisYear, "beginMonth" => 1, "endMonth" => $thisMonth, "purchTypeCN" => '' );
		}

		$this->assignFunc ( $initArr );
		$this->view ( 'purchtypesub' );
	}

	/**
	 *
	 * 供应商采购金额统计报表查询页面
	 * create by kuangzw
	 */
	function c_toPurchTypeSubSearch() {
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "年</option>";
		}
		$this->assign ( 'yearStr', $yearStr );
		$this->assignFunc ( $_GET );
		$this->view ( "purchtypesub-search" );
	}

	/****************** E 采购金额汇总表_采购类型 *********************/

	/****************** S 采购金额月度汇总表_物料信息 *********************/
	/**
	 * 列表显示
	 * create by kuangzw
	 */
	function c_toProInfoSub() {
		if (isset ( $_GET ['suppName'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = date ( 'm' ) * 1;

			//初始化数组
			$initArr = array ("beginYear" => $thisYear, "beginMonth" => $thisMonth, "endYear" => $thisYear, "endMonth" => $thisMonth,

			"suppId" => '', "suppName" => '',

			"productId" => '', "productNumb" => '', "productName" => '' );
		}

		$this->assignFunc ( $initArr );
		$this->view ( 'proinfosub' );
	}

	/**
	 *
	 * 供应商采购金额统计报表查询页面
	 * create by kuangzw
	 */
	function c_toProInfoSubSearch() {
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "年</option>";
		}
		$this->assign ( 'yearStr', $yearStr );
		$this->assignFunc ( $_GET );
		$this->view ( "proinfosub-search" );
	}

	/****************** E 采购金额月度汇总表_物料信息 *********************/

	/****************** S 涨价物料汇总表 *********************/
	/**
	 * 列表显示
	 * create by kuangzw
	 */
	function c_toProRiseInPrice() {
		if (isset ( $_GET ['suppName'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = date ( 'm' ) * 1;

			//初始化数组
			$initArr = array ("thisYear" => $thisYear, "thisMonth" => $thisMonth,

			"suppId" => '', "suppName" => '',

			"productId" => '', "productNumb" => '', "productName" => '' );

			//初始化价格缓存数据
			$this->service->initData_d ();
		}

		$this->assignFunc ( $initArr );
		$this->view ( 'proriseinprice' );
	}

	/**
	 *
	 * 涨价物料汇总表查询页
	 * create by kuangzw
	 */
	function c_toProRiseInPriceSearch() {
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "年</option>";
		}
		$this->assign ( 'yearStr', $yearStr );
		$this->assignFunc ( $_GET );
		$this->view ( "proriseinprice-search" );
	}

	/****************** E 涨价物料汇总表 *********************/

	/****************** S 降价物料汇总表 *********************/
	/**
	 * 列表显示
	 * create by kuangzw
	 */
	function c_toProReducatePrice() {
		if (isset ( $_GET ['suppName'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = date ( 'm' ) * 1;

			//初始化数组
			$initArr = array ("thisYear" => $thisYear, "thisMonth" => $thisMonth,

			"suppId" => '', "suppName" => '',

			"productId" => '', "productNumb" => '', "productName" => '' );

			//初始化价格缓存数据
			$this->service->initData_d ();
		}

		$this->assignFunc ( $initArr );
		$this->view ( 'proreducateprice' );
	}

	/**
	 *
	 * 涨价物料汇总表查询页
	 * create by kuangzw
	 */
	function c_toProReducatePriceSearch() {
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "年</option>";
		}
		$this->assign ( 'yearStr', $yearStr );
		$this->assignFunc ( $_GET );
		$this->view ( "proreducateprice-search" );
	}

	/****************** E 降价物料汇总表 *********************/


	/****************** S 已付款未按期到货汇总表 *********************/

	/**
	 * 已付款未按其到货汇总表
	 * create by kuangzw
	 */
	function c_toPayedNotArrival() {
		if (isset ( $_GET ['suppName'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = date ( 'm' ) * 1;
			//初始化数组
			$initArr = array ("beginYear" => $thisYear, "beginMonth" => 1, "endYear" => $thisYear, "endMonth" => $thisMonth,

			"sendName" => '', "sendId" => '',

			"suppId" => '', "suppName" => '',

			"purchTypeCN" => '',

			"hwapplyNumb" => '',

			"productId" => '', "productNumb" => '', "productName" => '' );
		}

		$this->assignFunc ( $initArr );
		$this->view ( "payednotarrival" );
	}

	/**
	 *
	 * 供应商采购金额统计报表查询页面
	 */
	function c_toPayedNotArrivalSearch() {
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "年</option>";
		}
		$this->assign ( 'yearStr', $yearStr );
		$this->assignFunc ( $_GET );
		$this->view ( "payednotarrival-search" );
	}

	/********************** E 已付款未按期到货汇总表 *********************/

	/********************** S 已付款未到发票汇总表 **********************/
	/**
	 * 已付款未到票汇总表
	 */
	function c_toPayedNotInvoice(){
		if (isset ( $_GET ['suppName'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = date ( 'm' ) * 1;
			//初始化数组
			$initArr = array ("beginYear" => $thisYear, "beginMonth" => 1, "endYear" => $thisYear, "endMonth" => $thisMonth,

			"sendName" => '', "sendId" => '',

			"suppId" => '', "suppName" => '',

			"purchTypeCN" => '',

			"hwapplyNumb" => '',

			"productId" => '', "productNumb" => '', "productName" => '' );
		}

		$this->assignFunc ( $initArr );
		$this->view ( "payednotinvoice" );
	}

	/**
	 * 已付款未到票汇总查询
	 */
	function c_toPayedNotInvoiceSearch(){
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "年</option>";
		}
		$this->assign ( 'yearStr', $yearStr );
		$this->assignFunc ( $_GET );
		$this->view ( "payednotinvoice-search" );
	}
	/********************** E 已付款未到发票汇总表 **********************/

	/******************** S 已付款退货物料汇总表 *************************/
	/**
	 * 报表页面
	 */
	function c_toPayedDelivery() {
		if (isset ( $_GET ['suppName'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = date ( 'm' ) * 1;
			//初始化数组
			$initArr = array ("beginYear" => $thisYear, "beginMonth" => 1, "endYear" => $thisYear, "endMonth" => $thisMonth,

			"sendName" => '', "sendId" => '',

			"suppId" => '', "suppName" => '',

			"hwapplyNumb" => '',

			"productId" => '', "productNumb" => '', "productName" => '' );
		}

		$this->assignFunc ( $initArr );
		$this->view ( "payeddelivery" );
	}

	/**
	 * 查询页面
	 */
	function c_toPayedDeliverySearch() {
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "年</option>";
		}
		$this->assign ( 'yearStr', $yearStr );
		$this->assignFunc ( $_GET );
		$this->view ( "payeddelivery-search" );
	}
	/******************** E 已付款退货物料汇总表 *************************/

	/******************** S 固定资产采购汇总表 *************************/
	/**
	 * 报表页面
	 */
	function c_toAssetsDeptSub() {
		$thisYear = date ( 'Y' );
		$yearStr = "";
		for($i = $thisYear; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "年</option>";
		}
		$this->assign ( 'yearStr', $yearStr );

		$this->assign ( 'quarterStr', "<option value=1>第1季度</option><option value=2>第2季度</option><option value=3>第3季度</option><option value=4>第4季度</option>" );

		if (isset ( $_GET ['thisYear'] )) {
			$initArr = $_GET;
		} else {
			$thisQuarter = intval((date('m') + 2)/3);

			$initArr = array(
				'thisYear' => $thisYear,
				'thisQuarter' => $thisQuarter
			);
		}

		$this->assignFunc ( $initArr );
		$this->view ( "assetsdeptsub" );
	}

	/******************** E 固定资产采购汇总表 *************************/

	/****************** S 采购付款金额月报表 *********************/
	/**
	 * 列表显示
	 * create by kuangzw
	 */
	function c_toPaySub() {
		if (isset ( $_GET ['thisYear'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = date ( 'm' ) * 1;

			//初始化数组
			$initArr = array ("thisYear" => $thisYear, "beginMonth" => 1, "endMonth" => $thisMonth, "purchTypeCN" => '' );
		}

		$this->assignFunc ( $initArr );
		$this->view ( 'paysub' );
	}

	/**
	 *
	 * 供应商采购金额统计报表查询页面
	 * create by kuangzw
	 */
	function c_toPaySubSearch() {
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "年</option>";
		}
		$this->assign ( 'yearStr', $yearStr );
		$this->assignFunc ( $_GET );
		$this->view ( "paysub-search" );
	}

	/****************** E 采购付款金额月报表 *********************/

	/****************************** 库存物料报表 ***********************************/
	/**
	 * 报表页面
	 */
	function c_toInventoryReport() {

		if (isset ( $_GET ['suppName'] )) {
			$initArr = $_GET;
		} else {
			//初始化数组
			$initArr = array ("beginYear" => 2011, "beginMonth" => 9, "endYear" => date ( 'Y' ), "endMonth" => date ( 'm' ),

			"suppId" => '', "suppName" => '', "suppCode" => '',

			"productId" => '', "productNumb" => '', "productName" => '' );
		}

		$this->assignFunc ( $initArr );
		$this->view ( "inventoryReport" );
	}

	/**
	 * 查询页面
	 */
	function c_toInventorySearch() {
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "年</option>";
		}
		$this->assign ( 'yearStr', $yearStr );
		$this->assignFunc ( $_GET );
		$this->view ( "inventoryReport-search" );
	}
	/**
	 *
	 * 入库时间明细报表
	 */
	function c_toInstockDatePage() {
		$productId = isset ( $_GET ['productId'] ) ? $_GET ['productId'] : "";
		$productCode = isset ( $_GET ['productCode'] ) ? $_GET ['productCode'] : "";
		$productName = isset ( $_GET ['productName'] ) ? $_GET ['productName'] : "";

		$this->assign ( "productId", $productId );
		$this->assign ( "productCode", $productCode );
		$this->assign ( "productName", $productName );
		$this->view ( "instockdate" );
	}

	/**
	 *
	 * 入库时间明细报表查询页面
	 */
	function c_toInstockDateSearch() {
		$productId = isset ( $_GET ['productId'] ) ? $_GET ['productId'] : "";
		$productCode = isset ( $_GET ['productCode'] ) ? $_GET ['productCode'] : "";
		$productName = isset ( $_GET ['productName'] ) ? $_GET ['productName'] : "";

		$this->assign ( "productId", $productId );
		$this->assign ( "productCode", $productCode );
		$this->assign ( "productName", $productName );
		$this->view ( "instockdate-search" );
	}
/**
	 *
	 * 发货执行明细明细报表
	 */
	function c_toOutDetailPage() {
		$productId = isset ( $_GET ['productId'] ) ? $_GET ['productId'] : "";
		$productCode = isset ( $_GET ['productCode'] ) ? $_GET ['productCode'] : "";
		$productName = isset ( $_GET ['productName'] ) ? $_GET ['productName'] : "";

		$this->assign ( "productId", $productId );
		$this->assign ( "productCode", $productCode );
		$this->assign ( "productName", $productName );
		$this->view ( "outdetail" );
	}

	/**
	 *
	 * 发货执行明细明细报表查询页面
	 */
	function c_toOutDetailSearch() {
		$productId = isset ( $_GET ['productId'] ) ? $_GET ['productId'] : "";
		$productCode = isset ( $_GET ['productCode'] ) ? $_GET ['productCode'] : "";
		$productName = isset ( $_GET ['productName'] ) ? $_GET ['productName'] : "";

		$this->assign ( "productId", $productId );
		$this->assign ( "productCode", $productCode );
		$this->assign ( "productName", $productName );
		$this->view ( "outdetail-search" );
	}
/**
	 *
	 * 价格报表
	 */
	function c_toPriceList() {
		$object=isset($_POST['contract'])?$_POST['contract']:"";
		$logic="";
		$field="";
		$relation="";
		$values="";
		if(is_array($object)){//判断是否传入查询条件
			foreach($object as $key=>$val){
				$logic.=$val['logic'].",";
				$field.=$val['field'].",";
				$relation.=$val['relation'].",";
				$values.=$val['values'].",";
			}
		 	$this->assign("logic",$logic);
		 	$this->assign("field",$field);
		 	$this->assign("relation",$relation);
		 	$this->assign("values",$values);
			$this->assign('endMonth','12');
		}else{
		 	$this->assign("logic",'');
		 	$this->assign("field",'');
		 	$this->assign("relation",'');
		 	$this->assign("values",'');
			$this->assign('endMonth',date("m"));
		}
		//获取数据
		$rows=$this->service->getEquPriceList_d ($object);
		$listStr=$this->service->getPriceHtml_d($rows['0']);
		if(is_array($object)){//判断是否传入查询条件
			foreach($object as $key=>$val){
				$logic.=$val['logic'].",";
				$field.=$val['field'].",";
				$relation.=$val['relation'].",";
				$values.=$val['values'].",";
			}
		 	$this->assign("logic",$logic);
		 	$this->assign("field",$field);
		 	$this->assign("relation",$relation);
		 	$this->assign("values",$values);
			$this->assign('endMonth',$rows['1']);
		}else{
		 	$this->assign("logic",'');
		 	$this->assign("field",'');
		 	$this->assign("relation",'');
		 	$this->assign("values",'');
			$this->assign('endMonth',date("m"));
		}
//		echo $rows[1];
		$this->assign('list',$listStr);
		$this->view ( "price-list" );
	}
		/**
	 *采购价格表 高级搜索
	 */
	function c_toPriceListSearch(){
		$beginDate=isset($_GET['beginDate'])?$_GET['beginDate']:"";
		$logic=isset($_GET['logic'])?$_GET['logic']:"";
		$field=isset($_GET['field'])?$_GET['field']:"";
		$relation=isset($_GET['relation'])?$_GET['relation']:"";
		$values=isset($_GET['values'])?$_GET['values']:"";
	 	$this->assign("beginDate",$beginDate);
	 	if($values){//判断是否传入查询条件
			$logicArr=explode(',',$logic);
			$fieldArr=explode(',',$field);
			$relationArr=explode(',',$relation);
			$valuesArr=explode(',',$values);
			$list=$this->service->selectList($logicArr,$fieldArr,$relationArr,$valuesArr);//查询条件模板
			$this->assign('list',$list);
	 	}else{
			$this->assign('list',"");
	 	}
	 	if(!empty($logic)){
	 		$number=count($logicArr)-1;
	 	}else{
			$number=0;
	 	}
	 	$this->assign('invnumber',$number);
		$this->view('pricelist-search');
	}

	/**
	 * 合同交付情况汇总报表
	 */
	function c_toContractDeliveryAll() {
		$searchYear = isset($_GET['searchYear']) ? $_GET['searchYear'] : date('Y');
		$this->assign('searchYear' ,$searchYear);
		$this->view('contract-delivery-all');
	}

	/**
	 * 已完成交付合同明细
	 */
	function c_toFinishContractDeliveryDetail() {
		$contractCode = isset($_GET['contractCode']) ? $_GET['contractCode'] : '';
		$this->assign('contractCode' ,$contractCode);
		$ExaDTOneStart = isset($_GET['ExaDTOneStart']) ? $_GET['ExaDTOneStart'] : '';
		$this->assign('ExaDTOneStart' ,$ExaDTOneStart);
		$ExaDTOneEnd = isset($_GET['ExaDTOneEnd']) ? $_GET['ExaDTOneEnd'] : '';
		$this->assign('ExaDTOneEnd' ,$ExaDTOneEnd);
		$this->view('finish-contract-delivery-detail');
	}

	/**
	 * 未完成合同明细（交付时间超过1个月）
	 */
	function c_toUnfinishContractDeliveryDetail() {
		$contractCode = isset($_GET['contractCode']) ? $_GET['contractCode'] : '';
		$this->assign('contractCode' ,$contractCode);
		$this->view('unfinish-contract-delivery-detail');
	}

	/**
	 * 已完成交付合同分析表
	 */
	function c_toFinishContractDeliveryAnalysis() {
		$searchYear = isset($_GET['searchYear']) ? $_GET['searchYear'] : date('Y');
		$this->assign('searchYear' ,$searchYear);
		$this->view('finish-contract-delivery-analysis');
	}

	/**
	 * 自产产品出库分析
	 */
	function c_toSellProductsOutboundAnalysis() {
		$searchYear = isset($_GET['searchYear']) ? $_GET['searchYear'] : date('Y');
		$this->assign('searchYear' ,$searchYear);
		$propertiesName = isset($_GET['propertiesName']) ? $_GET['propertiesName'] : '';
		$this->assign('propertiesName' ,$propertiesName);
		$this->view('sell-products-outbound-analysis');
	}

	/**
	 * 外购产品出库分析
	 */
	function c_toPurchasedProductsOutboundAnalysis() {
		$searchYear = isset($_GET['searchYear']) ? $_GET['searchYear'] : date('Y');
		$this->assign('searchYear' ,$searchYear);
		$budgetTypeName = isset($_GET['budgetTypeName']) ? $_GET['budgetTypeName'] : '';
		$this->assign('budgetTypeName' ,$budgetTypeName);
		$this->view('purchased-products-outbound-analysis');
	}

}
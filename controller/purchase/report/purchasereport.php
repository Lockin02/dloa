<?php
class controller_purchase_report_purchasereport extends controller_base_action {

	function __construct() {
		$this->objName = "purchasereport";
		$this->objPath = "purchase_report";
		parent::__construct ();
	}

	/**
	 *
	 * ��Ӧ�̲ɹ����ͳ�Ʊ���
	 */
	function c_toSupSubPage() {
		if (isset ( $_GET ['thisYear'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = date ( 'm' ) * 1;

			//��ʼ������
			$initArr = array ("thisYear" => $thisYear, "beginMonth" => 1, "endMonth" => $thisMonth, "purchTypeCN" => '', "suppId" => '', "suppName" => '' );
		}

		$this->assignFunc ( $initArr );
		$this->view ( "suppsub-detail" );
	}

	/**
	 *
	 * ��Ӧ�̲ɹ����ͳ�Ʊ����ѯҳ��
	 */
	function c_toSupSubPageSearch() {
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "��</option>";
		}
		$this->assign ( 'yearStr', $yearStr );
		$this->assignFunc ( $_GET );
		$this->view ( "suppsub-search" );
	}

	/****************** S �ɹ������ܱ�_�ɹ����� *********************/
	/**
	 * �б���ʾ
	 * create by kuangzw
	 */
	function c_toPurchTypeSub() {
		if (isset ( $_GET ['thisYear'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = date ( 'm' ) * 1;

			//��ʼ������
			$initArr = array ("thisYear" => $thisYear, "beginMonth" => 1, "endMonth" => $thisMonth, "purchTypeCN" => '' );
		}

		$this->assignFunc ( $initArr );
		$this->view ( 'purchtypesub' );
	}

	/**
	 *
	 * ��Ӧ�̲ɹ����ͳ�Ʊ����ѯҳ��
	 * create by kuangzw
	 */
	function c_toPurchTypeSubSearch() {
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "��</option>";
		}
		$this->assign ( 'yearStr', $yearStr );
		$this->assignFunc ( $_GET );
		$this->view ( "purchtypesub-search" );
	}

	/****************** E �ɹ������ܱ�_�ɹ����� *********************/

	/****************** S �ɹ�����¶Ȼ��ܱ�_������Ϣ *********************/
	/**
	 * �б���ʾ
	 * create by kuangzw
	 */
	function c_toProInfoSub() {
		if (isset ( $_GET ['suppName'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = date ( 'm' ) * 1;

			//��ʼ������
			$initArr = array ("beginYear" => $thisYear, "beginMonth" => $thisMonth, "endYear" => $thisYear, "endMonth" => $thisMonth,

			"suppId" => '', "suppName" => '',

			"productId" => '', "productNumb" => '', "productName" => '' );
		}

		$this->assignFunc ( $initArr );
		$this->view ( 'proinfosub' );
	}

	/**
	 *
	 * ��Ӧ�̲ɹ����ͳ�Ʊ����ѯҳ��
	 * create by kuangzw
	 */
	function c_toProInfoSubSearch() {
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "��</option>";
		}
		$this->assign ( 'yearStr', $yearStr );
		$this->assignFunc ( $_GET );
		$this->view ( "proinfosub-search" );
	}

	/****************** E �ɹ�����¶Ȼ��ܱ�_������Ϣ *********************/

	/****************** S �Ǽ����ϻ��ܱ� *********************/
	/**
	 * �б���ʾ
	 * create by kuangzw
	 */
	function c_toProRiseInPrice() {
		if (isset ( $_GET ['suppName'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = date ( 'm' ) * 1;

			//��ʼ������
			$initArr = array ("thisYear" => $thisYear, "thisMonth" => $thisMonth,

			"suppId" => '', "suppName" => '',

			"productId" => '', "productNumb" => '', "productName" => '' );

			//��ʼ���۸񻺴�����
			$this->service->initData_d ();
		}

		$this->assignFunc ( $initArr );
		$this->view ( 'proriseinprice' );
	}

	/**
	 *
	 * �Ǽ����ϻ��ܱ��ѯҳ
	 * create by kuangzw
	 */
	function c_toProRiseInPriceSearch() {
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "��</option>";
		}
		$this->assign ( 'yearStr', $yearStr );
		$this->assignFunc ( $_GET );
		$this->view ( "proriseinprice-search" );
	}

	/****************** E �Ǽ����ϻ��ܱ� *********************/

	/****************** S �������ϻ��ܱ� *********************/
	/**
	 * �б���ʾ
	 * create by kuangzw
	 */
	function c_toProReducatePrice() {
		if (isset ( $_GET ['suppName'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = date ( 'm' ) * 1;

			//��ʼ������
			$initArr = array ("thisYear" => $thisYear, "thisMonth" => $thisMonth,

			"suppId" => '', "suppName" => '',

			"productId" => '', "productNumb" => '', "productName" => '' );

			//��ʼ���۸񻺴�����
			$this->service->initData_d ();
		}

		$this->assignFunc ( $initArr );
		$this->view ( 'proreducateprice' );
	}

	/**
	 *
	 * �Ǽ����ϻ��ܱ��ѯҳ
	 * create by kuangzw
	 */
	function c_toProReducatePriceSearch() {
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "��</option>";
		}
		$this->assign ( 'yearStr', $yearStr );
		$this->assignFunc ( $_GET );
		$this->view ( "proreducateprice-search" );
	}

	/****************** E �������ϻ��ܱ� *********************/


	/****************** S �Ѹ���δ���ڵ������ܱ� *********************/

	/**
	 * �Ѹ���δ���䵽�����ܱ�
	 * create by kuangzw
	 */
	function c_toPayedNotArrival() {
		if (isset ( $_GET ['suppName'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = date ( 'm' ) * 1;
			//��ʼ������
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
	 * ��Ӧ�̲ɹ����ͳ�Ʊ����ѯҳ��
	 */
	function c_toPayedNotArrivalSearch() {
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "��</option>";
		}
		$this->assign ( 'yearStr', $yearStr );
		$this->assignFunc ( $_GET );
		$this->view ( "payednotarrival-search" );
	}

	/********************** E �Ѹ���δ���ڵ������ܱ� *********************/

	/********************** S �Ѹ���δ����Ʊ���ܱ� **********************/
	/**
	 * �Ѹ���δ��Ʊ���ܱ�
	 */
	function c_toPayedNotInvoice(){
		if (isset ( $_GET ['suppName'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = date ( 'm' ) * 1;
			//��ʼ������
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
	 * �Ѹ���δ��Ʊ���ܲ�ѯ
	 */
	function c_toPayedNotInvoiceSearch(){
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "��</option>";
		}
		$this->assign ( 'yearStr', $yearStr );
		$this->assignFunc ( $_GET );
		$this->view ( "payednotinvoice-search" );
	}
	/********************** E �Ѹ���δ����Ʊ���ܱ� **********************/

	/******************** S �Ѹ����˻����ϻ��ܱ� *************************/
	/**
	 * ����ҳ��
	 */
	function c_toPayedDelivery() {
		if (isset ( $_GET ['suppName'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = date ( 'm' ) * 1;
			//��ʼ������
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
	 * ��ѯҳ��
	 */
	function c_toPayedDeliverySearch() {
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "��</option>";
		}
		$this->assign ( 'yearStr', $yearStr );
		$this->assignFunc ( $_GET );
		$this->view ( "payeddelivery-search" );
	}
	/******************** E �Ѹ����˻����ϻ��ܱ� *************************/

	/******************** S �̶��ʲ��ɹ����ܱ� *************************/
	/**
	 * ����ҳ��
	 */
	function c_toAssetsDeptSub() {
		$thisYear = date ( 'Y' );
		$yearStr = "";
		for($i = $thisYear; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "��</option>";
		}
		$this->assign ( 'yearStr', $yearStr );

		$this->assign ( 'quarterStr', "<option value=1>��1����</option><option value=2>��2����</option><option value=3>��3����</option><option value=4>��4����</option>" );

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

	/******************** E �̶��ʲ��ɹ����ܱ� *************************/

	/****************** S �ɹ��������±��� *********************/
	/**
	 * �б���ʾ
	 * create by kuangzw
	 */
	function c_toPaySub() {
		if (isset ( $_GET ['thisYear'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = date ( 'm' ) * 1;

			//��ʼ������
			$initArr = array ("thisYear" => $thisYear, "beginMonth" => 1, "endMonth" => $thisMonth, "purchTypeCN" => '' );
		}

		$this->assignFunc ( $initArr );
		$this->view ( 'paysub' );
	}

	/**
	 *
	 * ��Ӧ�̲ɹ����ͳ�Ʊ����ѯҳ��
	 * create by kuangzw
	 */
	function c_toPaySubSearch() {
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "��</option>";
		}
		$this->assign ( 'yearStr', $yearStr );
		$this->assignFunc ( $_GET );
		$this->view ( "paysub-search" );
	}

	/****************** E �ɹ��������±��� *********************/

	/****************************** ������ϱ��� ***********************************/
	/**
	 * ����ҳ��
	 */
	function c_toInventoryReport() {

		if (isset ( $_GET ['suppName'] )) {
			$initArr = $_GET;
		} else {
			//��ʼ������
			$initArr = array ("beginYear" => 2011, "beginMonth" => 9, "endYear" => date ( 'Y' ), "endMonth" => date ( 'm' ),

			"suppId" => '', "suppName" => '', "suppCode" => '',

			"productId" => '', "productNumb" => '', "productName" => '' );
		}

		$this->assignFunc ( $initArr );
		$this->view ( "inventoryReport" );
	}

	/**
	 * ��ѯҳ��
	 */
	function c_toInventorySearch() {
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "��</option>";
		}
		$this->assign ( 'yearStr', $yearStr );
		$this->assignFunc ( $_GET );
		$this->view ( "inventoryReport-search" );
	}
	/**
	 *
	 * ���ʱ����ϸ����
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
	 * ���ʱ����ϸ�����ѯҳ��
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
	 * ����ִ����ϸ��ϸ����
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
	 * ����ִ����ϸ��ϸ�����ѯҳ��
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
	 * �۸񱨱�
	 */
	function c_toPriceList() {
		$object=isset($_POST['contract'])?$_POST['contract']:"";
		$logic="";
		$field="";
		$relation="";
		$values="";
		if(is_array($object)){//�ж��Ƿ����ѯ����
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
		//��ȡ����
		$rows=$this->service->getEquPriceList_d ($object);
		$listStr=$this->service->getPriceHtml_d($rows['0']);
		if(is_array($object)){//�ж��Ƿ����ѯ����
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
	 *�ɹ��۸�� �߼�����
	 */
	function c_toPriceListSearch(){
		$beginDate=isset($_GET['beginDate'])?$_GET['beginDate']:"";
		$logic=isset($_GET['logic'])?$_GET['logic']:"";
		$field=isset($_GET['field'])?$_GET['field']:"";
		$relation=isset($_GET['relation'])?$_GET['relation']:"";
		$values=isset($_GET['values'])?$_GET['values']:"";
	 	$this->assign("beginDate",$beginDate);
	 	if($values){//�ж��Ƿ����ѯ����
			$logicArr=explode(',',$logic);
			$fieldArr=explode(',',$field);
			$relationArr=explode(',',$relation);
			$valuesArr=explode(',',$values);
			$list=$this->service->selectList($logicArr,$fieldArr,$relationArr,$valuesArr);//��ѯ����ģ��
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
	 * ��ͬ����������ܱ���
	 */
	function c_toContractDeliveryAll() {
		$searchYear = isset($_GET['searchYear']) ? $_GET['searchYear'] : date('Y');
		$this->assign('searchYear' ,$searchYear);
		$this->view('contract-delivery-all');
	}

	/**
	 * ����ɽ�����ͬ��ϸ
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
	 * δ��ɺ�ͬ��ϸ������ʱ�䳬��1���£�
	 */
	function c_toUnfinishContractDeliveryDetail() {
		$contractCode = isset($_GET['contractCode']) ? $_GET['contractCode'] : '';
		$this->assign('contractCode' ,$contractCode);
		$this->view('unfinish-contract-delivery-detail');
	}

	/**
	 * ����ɽ�����ͬ������
	 */
	function c_toFinishContractDeliveryAnalysis() {
		$searchYear = isset($_GET['searchYear']) ? $_GET['searchYear'] : date('Y');
		$this->assign('searchYear' ,$searchYear);
		$this->view('finish-contract-delivery-analysis');
	}

	/**
	 * �Բ���Ʒ�������
	 */
	function c_toSellProductsOutboundAnalysis() {
		$searchYear = isset($_GET['searchYear']) ? $_GET['searchYear'] : date('Y');
		$this->assign('searchYear' ,$searchYear);
		$propertiesName = isset($_GET['propertiesName']) ? $_GET['propertiesName'] : '';
		$this->assign('propertiesName' ,$propertiesName);
		$this->view('sell-products-outbound-analysis');
	}

	/**
	 * �⹺��Ʒ�������
	 */
	function c_toPurchasedProductsOutboundAnalysis() {
		$searchYear = isset($_GET['searchYear']) ? $_GET['searchYear'] : date('Y');
		$this->assign('searchYear' ,$searchYear);
		$budgetTypeName = isset($_GET['budgetTypeName']) ? $_GET['budgetTypeName'] : '';
		$this->assign('budgetTypeName' ,$budgetTypeName);
		$this->view('purchased-products-outbound-analysis');
	}

}
<?php
/**
 * @author Show
 * @Date 2011年8月8日 星期一 11:00:07
 * @version 1.0
 * @description:合同结转表控制层
 */
class controller_finance_carriedforward_carriedforward extends controller_base_action {

	function __construct() {
		$this->objName = "carriedforward";
		$this->objPath = "finance_carriedforward";
		parent::__construct ();
	}

	/*
	 * 跳转到合同结转表
	 */
    function c_page() {
       $this->display('list');
    }

    /**
     * 重写pageJson
     */
    function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );

		$rows = $service->page_d ('selectJoin');
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

    /*************开票结转部分***********************/

	/**
	 * 开票结转选择页
	 */
	function c_toCarryInvoice(){
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "年</option>";
		}
		$this->assign ( 'yearStr', $yearStr );

		$this->assignFunc($this->service->getPeriod_d());
		$this->display('carryinvoicesearch');
	}

	/**
	 * 开票结转显示页
	 */
	function c_carryInvoiceList(){
		$dataArr = $this->service->getPeriod_d();
    	//获取前台传参
    	$carryObj = $_GET[$this->objName];
		//根据参数获取数据集
    	$rows = $this->service->getInvoiceDetail_d($carryObj,$dataArr);
    	//渲染列表
    	$rs = $this->service->showInvoiceDetail($rows,$dataArr);
    	$this->assign('list',$rs[0]);
    	$this->assign('countNum',$rs[1]);

    	//获取所有已钩稽单据
    	$this->assign('ids',$this->service->getInvoiceHooked_d($carryObj,$dataArr));

    	$this->assignFunc($carryObj);

    	$this->assign('sysYear',$dataArr['thisYear']);
    	$this->assign('sysMonth',$dataArr['thisMonth']);
		$this->display('carryinvoicelist');
	}

	/**
	 * 开票结转功能
	 */
	function c_carryInvoice(){
    	$ids = $this->service->carryInvoice_d($_POST['data']);
		if($ids){
			echo $ids;
		}else if( $ids === 0){
			echo 0;
		}else{
			echo null;
		}
		exit();
	}

	/**
	 * 开票结转改 包含分页
	 */
	function c_carryInvoiceList2(){
		$service = $this->service;
		$dataArr = $service->getPeriod_d();
    	//获取前台传参
    	$carryObj = $_GET;
    	$this->assignFunc($carryObj);

		//获取本期开过票的合同数量
		$rows = $service->getThisPeriodInvOrder_d($carryObj,$dataArr);

		$initRows = $service->showInvoiceDetail2($rows,$carryObj,$dataArr);
		$this->assign('list',$initRows[0]);

    	//获取所有已钩稽单据
    	$this->assign('ids',$service->getInvoiceHooked2_d($carryObj,$dataArr,$initRows[1]));

		//渲染系统时间
    	$this->assign('sysYear',$dataArr['thisYear']);
    	$this->assign('sysMonth',$dataArr['thisMonth']);
		//分页
		$this->pageShowAssign();

		$this->display('carryinvoicelist2');
	}

	/**
	 * 开票结转功能
	 */
	function c_carryInvoice2(){
    	$ids = $this->service->carryInvoice2_d($_POST['data']);
		if($ids){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}


	/**
	 * 新发票结转功能
	 */
	function c_carryInvoiceList3(){
		$service = $this->service;
		$dataArr = $service->getPeriod_d();
    	//获取前台传参
    	$carryObj = $_GET;
    	$this->assignFunc($carryObj);

    	//获取本期开过票的合同
		$rows = $service->getThisPeriodInvOrder_d($carryObj,$dataArr);

		//获取合同对应开票详细
		$rows = $service->getInvoiceByOrder_d($rows,$carryObj);

		$initRows = $service->showInvoiceDetail3($rows,$carryObj,$dataArr);
		$this->assign('list',$initRows[0]);

		//渲染系统时间
    	$this->assign('sysYear',$dataArr['thisYear']);
    	$this->assign('sysMonth',$dataArr['thisMonth']);
		//分页
		$this->pageShowAssign();

		$this->display('carryinvoicelist3');
	}

	/**
	 * 根据合同信息获取出库信息
	 */
	function c_getOutstockByOrder(){
		$rows = $this->service->getOutstockByOrder_d($_POST);
		echo util_jsonUtil::iconvGB2UTF($this->service->stockList($rows));
	}

	/**
	 * 根据票详细id获取出库信息
	 */
	function c_getOutstockByInvoiceDetailId(){
		$service = $this->service;
		$dataArr = $service->getPeriod_d();
		$rows = $this->service->getOutstockByInvoiceDetailId_d($_POST,$dataArr);
		echo util_jsonUtil::iconvGB2UTF($this->service->stockList($rows,$_POST));
	}

	/**
	 * 出库单详细钩稽
	 */
	function c_outstockDetailCarryView(){
		$object = $_GET;
		$rows = $this->service->getOutstockDetailById_d($object['outstockId'],$object);

		$this->assign('outstockdetail',$this->service->showOutStockDetailCarry($rows,$object));
		$this->display('outstockdetailview');
	}

	/**
	 * 出库单详细钩稽 - 保存功能
	 */
	function c_outstockDetailCarry(){
		$rs = $this->service->outstockDetailCarry_d($_POST[$this->objName]);
		if($rs){
			echo "<script>self.parent.getStockoutInfo($rs,1);if(self.parent.tb_remove)self.parent.tb_remove();alert('钩稽完成');if(self.parent.show_page)self.parent.show_page(1);if(!self.parent.tb_remove)window.close();</script>";
		}else{
			msg('钩稽失败');
		}
		exit();
	}


    /*************开票结转部分**********************/


    /*************出库结转部分**********************/

    /**
     * 出库结转选择页
     */
    function c_toCarryOutStock(){
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "年</option>";
		}
		$this->assign ( 'yearStr', $yearStr );

		$this->assignFunc($this->service->getPeriod_d());
		$this->display('carryoutstocksearch');
    }

    /**
     * 出库结转显示页面
     */
    function c_carryOutStockList(){
    	//获取前台传参
    	$carryObj = $_GET[$this->objName];
		//根据参数获取数据集
    	$rows = $this->service->getOutStockDetail_d($carryObj);
    	//渲染列表
    	$rs = $this->service->showOutStockDetail($rows,$carryObj);
    	$this->assign('list',$rs[0]);
    	$this->assign('countNum',$rs[1]);

    	//获取所有已钩稽单据
    	$this->assign('ids',$this->service->getHooked_d($carryObj));

    	$this->assignFunc($carryObj);

    	//获取当前财务期
		$dataArr = $this->service->getPeriod_d();
		$this->assignFunc($dataArr);

    	$this->display('carryoutstocklist');
    }

    /**
     * 出库结转功能
     */
    function c_carryOutStock(){
    	$ids = $this->service->carryOutStock_d($_POST['data']);
		if($ids){
			echo $ids;
		}else if( $ids === 0){
			echo 0;
		}else{
			echo null;
		}
		exit();
    }

    /*************出库结转部分**********************/

    /**
	 * 重写init
	 */
	function c_init() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->assign('outStockType',$this->getDataNameByCode($obj['outStockType']));
			$this->assign('saleType',$this->getDataNameByCode($obj['saleType']));
			$this->display ( 'view' );
		} else {
			$this->display ( 'edit' );
		}
	}

	/**
	 * 合同类型转换
	 */
	function contractTypeChange($orgType){
		$newType = null;
		switch($orgType){
			case 'oa_sale_order' : $newType = '销售合同';break;
			case 'oa_sale_service' : $newType = '服务合同';break;
			case 'oa_sale_lease' : $newType = '租赁合同';break;
			case 'oa_sale_rdproject' : $newType = '研发合同';break;
		}
		return $newType;
	}

	/**
	 * 返回发票是否已经被结转
	 */
	function c_invoiceIsCarried(){
		if($this->service->invoiceIsCarried_d($_POST['id'])){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}
}
?>
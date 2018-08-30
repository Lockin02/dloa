<?php
/**
 * @author Show
 * @Date 2011��8��8�� ����һ 11:00:07
 * @version 1.0
 * @description:��ͬ��ת����Ʋ�
 */
class controller_finance_carriedforward_carriedforward extends controller_base_action {

	function __construct() {
		$this->objName = "carriedforward";
		$this->objPath = "finance_carriedforward";
		parent::__construct ();
	}

	/*
	 * ��ת����ͬ��ת��
	 */
    function c_page() {
       $this->display('list');
    }

    /**
     * ��дpageJson
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

    /*************��Ʊ��ת����***********************/

	/**
	 * ��Ʊ��תѡ��ҳ
	 */
	function c_toCarryInvoice(){
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "��</option>";
		}
		$this->assign ( 'yearStr', $yearStr );

		$this->assignFunc($this->service->getPeriod_d());
		$this->display('carryinvoicesearch');
	}

	/**
	 * ��Ʊ��ת��ʾҳ
	 */
	function c_carryInvoiceList(){
		$dataArr = $this->service->getPeriod_d();
    	//��ȡǰ̨����
    	$carryObj = $_GET[$this->objName];
		//���ݲ�����ȡ���ݼ�
    	$rows = $this->service->getInvoiceDetail_d($carryObj,$dataArr);
    	//��Ⱦ�б�
    	$rs = $this->service->showInvoiceDetail($rows,$dataArr);
    	$this->assign('list',$rs[0]);
    	$this->assign('countNum',$rs[1]);

    	//��ȡ�����ѹ�������
    	$this->assign('ids',$this->service->getInvoiceHooked_d($carryObj,$dataArr));

    	$this->assignFunc($carryObj);

    	$this->assign('sysYear',$dataArr['thisYear']);
    	$this->assign('sysMonth',$dataArr['thisMonth']);
		$this->display('carryinvoicelist');
	}

	/**
	 * ��Ʊ��ת����
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
	 * ��Ʊ��ת�� ������ҳ
	 */
	function c_carryInvoiceList2(){
		$service = $this->service;
		$dataArr = $service->getPeriod_d();
    	//��ȡǰ̨����
    	$carryObj = $_GET;
    	$this->assignFunc($carryObj);

		//��ȡ���ڿ���Ʊ�ĺ�ͬ����
		$rows = $service->getThisPeriodInvOrder_d($carryObj,$dataArr);

		$initRows = $service->showInvoiceDetail2($rows,$carryObj,$dataArr);
		$this->assign('list',$initRows[0]);

    	//��ȡ�����ѹ�������
    	$this->assign('ids',$service->getInvoiceHooked2_d($carryObj,$dataArr,$initRows[1]));

		//��Ⱦϵͳʱ��
    	$this->assign('sysYear',$dataArr['thisYear']);
    	$this->assign('sysMonth',$dataArr['thisMonth']);
		//��ҳ
		$this->pageShowAssign();

		$this->display('carryinvoicelist2');
	}

	/**
	 * ��Ʊ��ת����
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
	 * �·�Ʊ��ת����
	 */
	function c_carryInvoiceList3(){
		$service = $this->service;
		$dataArr = $service->getPeriod_d();
    	//��ȡǰ̨����
    	$carryObj = $_GET;
    	$this->assignFunc($carryObj);

    	//��ȡ���ڿ���Ʊ�ĺ�ͬ
		$rows = $service->getThisPeriodInvOrder_d($carryObj,$dataArr);

		//��ȡ��ͬ��Ӧ��Ʊ��ϸ
		$rows = $service->getInvoiceByOrder_d($rows,$carryObj);

		$initRows = $service->showInvoiceDetail3($rows,$carryObj,$dataArr);
		$this->assign('list',$initRows[0]);

		//��Ⱦϵͳʱ��
    	$this->assign('sysYear',$dataArr['thisYear']);
    	$this->assign('sysMonth',$dataArr['thisMonth']);
		//��ҳ
		$this->pageShowAssign();

		$this->display('carryinvoicelist3');
	}

	/**
	 * ���ݺ�ͬ��Ϣ��ȡ������Ϣ
	 */
	function c_getOutstockByOrder(){
		$rows = $this->service->getOutstockByOrder_d($_POST);
		echo util_jsonUtil::iconvGB2UTF($this->service->stockList($rows));
	}

	/**
	 * ����Ʊ��ϸid��ȡ������Ϣ
	 */
	function c_getOutstockByInvoiceDetailId(){
		$service = $this->service;
		$dataArr = $service->getPeriod_d();
		$rows = $this->service->getOutstockByInvoiceDetailId_d($_POST,$dataArr);
		echo util_jsonUtil::iconvGB2UTF($this->service->stockList($rows,$_POST));
	}

	/**
	 * ���ⵥ��ϸ����
	 */
	function c_outstockDetailCarryView(){
		$object = $_GET;
		$rows = $this->service->getOutstockDetailById_d($object['outstockId'],$object);

		$this->assign('outstockdetail',$this->service->showOutStockDetailCarry($rows,$object));
		$this->display('outstockdetailview');
	}

	/**
	 * ���ⵥ��ϸ���� - ���湦��
	 */
	function c_outstockDetailCarry(){
		$rs = $this->service->outstockDetailCarry_d($_POST[$this->objName]);
		if($rs){
			echo "<script>self.parent.getStockoutInfo($rs,1);if(self.parent.tb_remove)self.parent.tb_remove();alert('�������');if(self.parent.show_page)self.parent.show_page(1);if(!self.parent.tb_remove)window.close();</script>";
		}else{
			msg('����ʧ��');
		}
		exit();
	}


    /*************��Ʊ��ת����**********************/


    /*************�����ת����**********************/

    /**
     * �����תѡ��ҳ
     */
    function c_toCarryOutStock(){
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "��</option>";
		}
		$this->assign ( 'yearStr', $yearStr );

		$this->assignFunc($this->service->getPeriod_d());
		$this->display('carryoutstocksearch');
    }

    /**
     * �����ת��ʾҳ��
     */
    function c_carryOutStockList(){
    	//��ȡǰ̨����
    	$carryObj = $_GET[$this->objName];
		//���ݲ�����ȡ���ݼ�
    	$rows = $this->service->getOutStockDetail_d($carryObj);
    	//��Ⱦ�б�
    	$rs = $this->service->showOutStockDetail($rows,$carryObj);
    	$this->assign('list',$rs[0]);
    	$this->assign('countNum',$rs[1]);

    	//��ȡ�����ѹ�������
    	$this->assign('ids',$this->service->getHooked_d($carryObj));

    	$this->assignFunc($carryObj);

    	//��ȡ��ǰ������
		$dataArr = $this->service->getPeriod_d();
		$this->assignFunc($dataArr);

    	$this->display('carryoutstocklist');
    }

    /**
     * �����ת����
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

    /*************�����ת����**********************/

    /**
	 * ��дinit
	 */
	function c_init() {
		$this->permCheck (); //��ȫУ��
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
	 * ��ͬ����ת��
	 */
	function contractTypeChange($orgType){
		$newType = null;
		switch($orgType){
			case 'oa_sale_order' : $newType = '���ۺ�ͬ';break;
			case 'oa_sale_service' : $newType = '�����ͬ';break;
			case 'oa_sale_lease' : $newType = '���޺�ͬ';break;
			case 'oa_sale_rdproject' : $newType = '�з���ͬ';break;
		}
		return $newType;
	}

	/**
	 * ���ط�Ʊ�Ƿ��Ѿ�����ת
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
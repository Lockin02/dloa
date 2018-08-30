<?php
/**
 * @author huangzf
 * @Date 2011��5��11�� 16:54:05
 * @version 1.0
 * @description:��ⵥ�����嵥���Ʋ�
 */
class controller_stock_instock_stockinitem extends controller_base_action {

	function __construct() {
		$this->objName = "stockinitem";
		$this->objPath = "stock_instock";
		parent::__construct ();
	 }

	/*
	 * ��ת����ⵥ�����嵥
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

    /**
     *
     * �⹺��⹳��ѡ����ⵥ
     */
	function c_instockHookPage () {
		$searchArr=array(
							"supplierId" => $_GET['supplierId']
						);
		if(isset($_GET['productCode'])){
			$searchArr['productCode']=$_GET['productCode'];
		}
		if(isset($_GET['productName'])){
			$searchArr['productName']=$_GET['productName'];
		}
		if(isset($_GET['docCode'])){
			$searchArr['docCode']=$_GET['docCode'];
		}
		if(isset($_GET['purchOrderIds'])){
			if(!empty($_GET['purchOrderIds'])){
				$searchArr['purchOrderIds']=$_GET['purchOrderIds'];				
			}
		}
						
		$service = $this->service;
		$rows = $service->getInproBySupplier($searchArr,'CGFPZT-WGJ,CGFPZT-BFGJ','RKPURCHASE');
		$service->count=count($rows);
		$this->pageShowAssign ();//��ҳ
		$this->assignFunc($_GET);
//        $objNo = isset($_GET['objNo']) ? $_GET['objNo'] : null;
//        $objCodeSearch = isset($_GET['objCodeSearch']) ? $_GET['objCodeSearch'] : null;
//        $this->assign('searchId', empty($objNo) ? $objCodeSearch : $objNo);
		$this->show->assign ( 'list', $service->showHookInfo ( $rows) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-hook-page' );
	}

	/**
	 * ��ת���ݹ��ػ�
	 * kzw
	 */
	function c_instockReleasePage () {
		$supplierId = $_GET['supplierId'];
		$service = $this->service;
		$rows = $service->getInproBySupplier($supplierId,'CGFPZT-GJHS');
		$this->pageShowAssign ();//��ҳ
		$this->show->assign ( 'list', $service->showHookInfo ( $rows) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-hook-page' );
	}
 }
?>
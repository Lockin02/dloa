<?php
/**
 * @author huangzf
 * @Date 2011年5月11日 16:54:05
 * @version 1.0
 * @description:入库单物料清单控制层
 */
class controller_stock_instock_stockinitem extends controller_base_action {

	function __construct() {
		$this->objName = "stockinitem";
		$this->objPath = "stock_instock";
		parent::__construct ();
	 }

	/*
	 * 跳转到入库单物料清单
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

    /**
     *
     * 外购入库钩稽选择入库单
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
		$this->pageShowAssign ();//分页
		$this->assignFunc($_GET);
//        $objNo = isset($_GET['objNo']) ? $_GET['objNo'] : null;
//        $objCodeSearch = isset($_GET['objCodeSearch']) ? $_GET['objCodeSearch'] : null;
//        $this->assign('searchId', empty($objNo) ? $objCodeSearch : $objNo);
		$this->show->assign ( 'list', $service->showHookInfo ( $rows) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-hook-page' );
	}

	/**
	 * 跳转到暂估重回
	 * kzw
	 */
	function c_instockReleasePage () {
		$supplierId = $_GET['supplierId'];
		$service = $this->service;
		$rows = $service->getInproBySupplier($supplierId,'CGFPZT-GJHS');
		$this->pageShowAssign ();//分页
		$this->show->assign ( 'list', $service->showHookInfo ( $rows) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-hook-page' );
	}
 }
?>
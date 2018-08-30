<?php
/**
 * @author Show
 * @Date 2011年1月13日 星期四 17:22:36
 * @version 1.0
 * @description:补差单控制层
 */
class controller_finance_adjust_adjust extends controller_base_action {

	function __construct() {
		$this->objName = "adjust";
		$this->objPath = "finance_adjust";
		parent::__construct ();
	 }

	/*
	 * 跳转到补差单
	 */
    function c_page() {
      $this->display('list');
    }

    /**************************报表列表页面***************************/
    /**
     * 报表工具查询列表
     */
    function c_listInfo(){
        unset($_GET['action']);
        unset($_GET['model']);
        $thisObj = !empty($_GET) ? $_GET : array(
                                        'formDateBegin'=>'','formDateEnd'=>'','supplierName'=>'','productId'=>'',
                                        'productNo'=>'','supplierId'=>'');
        $this->assignFunc($thisObj);
        $this->display('listinfo');
    }

    /**
     * 采购发票高级搜索
     */
    function c_toListInfoSearch(){
        $this->showDatadicts ( array ('invType' => 'FPLX' ),$_GET['invType']);
        unset($_GET['invType']);

        $this->assignFunc($_GET);
        $this->display('listinfo-search');
    }
    /**************************报表列表页面***************************/
}
?>
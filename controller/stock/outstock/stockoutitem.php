<?php
/**
 * @author Administrator
 * @Date 2011��3��13�� 13:41:28
 * @version 1.0
 * @description:���ⵥ�����嵥���Ʋ� 
 */
class controller_stock_outstock_stockoutitem extends controller_base_action {

	function __construct() {
		$this->objName = "stockoutitem";
		$this->objPath = "stock_outstock";
		parent::__construct ();
	 }
    
	/*
	 * ��ת�����ⵥ�����嵥
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
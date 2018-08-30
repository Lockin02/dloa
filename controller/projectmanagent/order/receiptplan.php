<?php
/**
 * @author Administrator
 * @Date 2011年3月4日 15:13:34
 * @version 1.0
 * @description:订单收款计划控制层 
 */
class controller_projectmanagent_order_receiptplan extends controller_base_action {

	function __construct() {
		$this->objName = "receiptplan";
		$this->objPath = "projectmanagent_order";
		parent::__construct ();
	 }
    
	/*
	 * 跳转到订单收款计划
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
<?php
/**
 * @author Administrator
 * @Date 2011年3月4日 15:06:47
 * @version 1.0
 * @description:订单开票计划控制层 
 */
class controller_projectmanagent_order_invoice extends controller_base_action {

	function __construct() {
		$this->objName = "invoice";
		$this->objPath = "projectmanagent_order";
		parent::__construct ();
	 }
    
	/*
	 * 跳转到订单开票计划
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
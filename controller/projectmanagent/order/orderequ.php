<?php
/**
 * @author Administrator
 * @Date 2011年3月4日 10:42:18
 * @version 1.0
 * @description:订单产品清单控制层 
 */
class controller_projectmanagent_order_orderequ extends controller_base_action {

	function __construct() {
		$this->objName = "orderequ";
		$this->objPath = "projectmanagent_order";
		parent::__construct ();
	 }
    
	/*
	 * 跳转到订单产品清单
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
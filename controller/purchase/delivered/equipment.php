<?php
/**
 * @author suxc
 * @Date 2011年5月6日 10:27:57
 * @version 1.0
 * @description:退料通知单物料清单信息控制层 
 */
class controller_purchase_delivered_equipment extends controller_base_action {

	function __construct() {
		$this->objName = "equipment";
		$this->objPath = "purchase_delivered";
		parent::__construct ();
	 }
    
	/*
	 * 跳转到退料通知单物料清单信息
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
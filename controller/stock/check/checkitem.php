<?php
/**
 * @author Administrator
 * @Date 2011年8月9日 19:38:28
 * @version 1.0
 * @description:盘点物料清单控制层 
 */
class controller_stock_check_checkitem extends controller_base_action {

	function __construct() {
		$this->objName = "checkitem";
		$this->objPath = "stock_check";
		parent::__construct ();
	 }
    
	/*
	 * 跳转到盘点物料清单
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
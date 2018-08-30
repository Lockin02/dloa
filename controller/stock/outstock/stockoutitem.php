<?php
/**
 * @author Administrator
 * @Date 2011年3月13日 13:41:28
 * @version 1.0
 * @description:出库单物料清单控制层 
 */
class controller_stock_outstock_stockoutitem extends controller_base_action {

	function __construct() {
		$this->objName = "stockoutitem";
		$this->objPath = "stock_outstock";
		parent::__construct ();
	 }
    
	/*
	 * 跳转到出库单物料清单
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
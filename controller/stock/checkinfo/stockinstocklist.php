<?php
/**
 * @author Administrator
 * @Date 2010年12月21日 21:00:18
 * @version 1.0
 * @description:盘点入库清单控制层 
 */
class controller_stock_checkinfo_stockinstocklist extends controller_base_action {

	function __construct() {
		$this->objName = "stockinstocklist";
		$this->objPath = "stock_checkinfo";
		parent::__construct ();
	 }
    
	/*
	 * 跳转到盘点入库清单
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
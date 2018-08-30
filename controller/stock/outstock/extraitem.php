<?php
/**
 * @author Administrator
 * @Date 2011年3月13日 13:41:42
 * @version 1.0
 * @description:出库单物料额外配套清单控制层 包括产品的包装物、硬件产品对应的配件
 */
class controller_stock_outstock_extraitem extends controller_base_action {

	function __construct() {
		$this->objName = "extraitem";
		$this->objPath = "stock_outstock";
		parent::__construct ();
	 }
    
	/*
	 * 跳转到出库单物料额外配套清单
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
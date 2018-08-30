<?php
/**
 * @author Administrator
 * @Date 2011年5月18日 10:50:36
 * @version 1.0
 * @description:物料批次号台账控制层 
 */
class controller_stock_batchno_batchno extends controller_base_action {

	function __construct() {
		$this->objName = "batchno";
		$this->objPath = "stock_batchno";
		parent::__construct ();
	 }
    
	/*
	 * 跳转到物料批次号台账
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
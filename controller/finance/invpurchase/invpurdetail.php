<?php
/**
 * @author Show
 * @Date 2010年12月21日 星期二 15:54:46
 * @version 1.0
 * @description:采购发票条目控制层 
 */
class controller_finance_invpurchase_invpurdetail extends controller_base_action {

	function __construct() {
		$this->objName = "invpurdetail";
		$this->objPath = "finance_invpurchase";
		parent::__construct ();
	 }
    
	/*
	 * 跳转到采购发票条目
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
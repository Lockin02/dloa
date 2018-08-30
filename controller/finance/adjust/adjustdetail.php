<?php
/**
 * @author Show
 * @Date 2011年1月13日 星期四 17:22:31
 * @version 1.0
 * @description:补差单条目控制层
 */
class controller_finance_adjust_adjustdetail extends controller_base_action {

	function __construct() {
		$this->objName = "adjustdetail";
		$this->objPath = "finance_adjust";
		parent::__construct ();
	 }

	/*
	 * 跳转到补差单条目
	 */
    function c_page() {
      $this->display('list');
    }
 }
?>
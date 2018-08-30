<?php
/**
 * @author Show
 * @Date 2011年3月4日 星期五 10:07:57
 * @version 1.0
 * @description:财务开票额度记录表控制层
 */
class controller_finance_invoice_yearPlan extends controller_base_action {

	function __construct() {
		$this->objName = "yearPlan";
		$this->objPath = "finance_invoice";
		parent::__construct ();
	 }

	/*
	 * 跳转到财务开票额度记录表
	 */
    function c_page() {
      $this->display('list');
    }

    /**
     * 重写toAdd
     */
	function c_toAdd(){
		$this->assign('year',date('Y'));
		$this->display('add');
	}
 }
?>
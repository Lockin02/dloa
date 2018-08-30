<?php

/**
 * @author Show
 * @Date 2012年7月31日 20:24:45
 * @version 1.0
 * @description:费用发票明细控制层
 */
class controller_engineering_cost_esminvoicedetail extends controller_base_action {

	function __construct() {
		$this->objName = "esminvoicedetail";
		$this->objPath = "engineering_cost";
		parent :: __construct();
	}

	/**
	 * 跳转到费用发票明细列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	  * 跳转到新增费用发票明细页面
	  */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	  * 跳转到编辑费用发票明细页面
	  */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	  * 跳转到查看费用发票明细页面
	  */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}
}
?>
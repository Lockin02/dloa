<?php

/**
 * @author Show
 * @Date 2012年10月15日 星期一 9:44:04
 * @version 1.0
 * @description:发票汇总单控制层
 */
class controller_finance_expense_exbill extends controller_base_action {

	function __construct() {
		$this->objName = "exbill";
		$this->objPath = "finance_expense";
		parent :: __construct();
	}

	/**
	 * 跳转到发票汇总单列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增发票汇总单页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑发票汇总单页面
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
	 * 跳转到查看发票汇总单页面
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
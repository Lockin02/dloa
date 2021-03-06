<?php

/**
 * @author Show
 * @Date 2012年12月6日 星期四 14:29:37
 * @version 1.0
 * @description:报销申请单控制层 DetailType
 1.部门报销
 2.(工程)项目报销
 3.研发项目报销
 4.售前费用
 5.售后费用
 */
class controller_finance_expense_expenselist extends controller_base_action {
	function __construct() {
		$this->objName = "expenselist";
		$this->objPath = "finance_expense";
		parent :: __construct();
	}

	/**
	 * 跳转到报销申请单列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增报销申请单页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑报销申请单页面
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
	 * 跳转到查看报销申请单页面
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
<?php

/**
 * @author Show
 * @Date 2012年10月11日 星期四 10:02:05
 * @version 1.0
 * @description:费用汇总表明细备注信息控制层
 */
class controller_finance_expense_exsummarydetail extends controller_base_action {

	function __construct() {
		$this->objName = "exsummarydetail";
		$this->objPath = "finance_expense";
		parent :: __construct();
	}

	/**
	 * 跳转到费用汇总表明细备注信息列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
		 * 跳转到新增费用汇总表明细备注信息页面
		 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
		 * 跳转到编辑费用汇总表明细备注信息页面
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
		 * 跳转到查看费用汇总表明细备注信息页面
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
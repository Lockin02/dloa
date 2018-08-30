<?php
/**
 * @author zengq
 * @Date 2012年8月20日 10:57:17
 * @version 1.0
 * @description:盘点管理->属性管理 控制层
 */
class controller_hr_inventory_attr extends controller_base_action {

	function __construct() {
			$this->objName = "attr";
			$this->objPath = "hr_inventory";
			parent :: __construct();
		}
	/*
	 * 跳转到属性管理列表
	 */
	function c_page() {
		$this->view('list');
	}
	/*
	 * 跳转到查看属性页面
	 */
	function c_toView() {
		$obj = $this->service->get_d($_GET['id']);
		$obj['attrType'] = $obj['attrType']==0?"文本框":"下拉框";
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}
	/*
	 * 跳转到属性新增页面
	 */
	function c_toAdd() {
		$this->view('add');
	}
	/*
	 * 跳转到属性编辑页面
	 */
	function c_toEdit() {
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}
}
?>

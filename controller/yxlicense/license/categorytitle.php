<?php

class controller_yxlicense_license_categorytitle extends controller_base_action {

	function __construct() {
		$this->objName = "categorytitle";
		$this->objPath = "yxlicense_license";
		parent :: __construct();
	}

	/**
	 * 跳转到列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑页面
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
	 * 跳转到查看页面
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
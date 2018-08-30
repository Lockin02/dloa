<?php

/**
 * @author Show
 * @Date 2013年5月20日 星期一 13:48:57
 * @version 1.0
 * @description:质检方案分录控制层
 */
class controller_produce_quality_quaprogramitem extends controller_base_action {

	function __construct() {
		$this->objName = "quaprogramitem";
		$this->objPath = "produce_quality";
		parent :: __construct();
	}

	/*
	 * 跳转到质检方案分录列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增质检方案分录页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑质检方案分录页面
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
	 * 跳转到查看质检方案分录页面
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
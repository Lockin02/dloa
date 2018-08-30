<?php
/**
 * @author sony
 * @Date 2013年7月10日 17:37:38
 * @version 1.0
 * @description:改签子表字段控制层
 */
class controller_flights_message_messageitem extends controller_base_action {

	function __construct() {
		$this->objName = "messageitem";
		$this->objPath = "flights_message";
		parent :: __construct();
	}

	/**
	 * 跳转到改签子表字段列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增改签子表字段页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑改签子表字段页面
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
	 * 跳转到查看改签子表字段页面
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
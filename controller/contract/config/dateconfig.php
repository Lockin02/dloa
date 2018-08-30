<?php
/**
 * @author Show
 * @Date 2013年7月15日 10:44:32
 * @version 1.0
 * @description:日期设置控制层
 */
class controller_contract_config_dateconfig extends controller_base_action {

	function __construct() {
		$this->objName = "dateconfig";
		$this->objPath = "contract_config";
		parent :: __construct();
	}

	/**
	 * 跳转到日期设置列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增日期设置页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑日期设置页面
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
	 * 跳转到查看日期设置页面
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
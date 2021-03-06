<?php
/**
 * @author Show
 * @Date 2012年12月10日 星期一 14:20:22
 * @version 1.0
 * @description:项目指标选项控制层
 */
class controller_engineering_assess_esmassprooption extends controller_base_action {

	function __construct() {
		$this->objName = "esmassprooption";
		$this->objPath = "engineering_assess";
		parent :: __construct();
	}

	/**
	 * 跳转到项目指标选项列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增项目指标选项页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑项目指标选项页面
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
	 * 跳转到查看项目指标选项页面
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
<?php
/**
 * @author Show
 * @Date 2012年12月3日 星期一 10:42:07
 * @version 1.0
 * @description:周报评价指标控制层
 */
class controller_engineering_worklog_esmrsindex extends controller_base_action {

	function __construct() {
		$this->objName = "esmrsindex";
		$this->objPath = "engineering_worklog";
		parent :: __construct();
	}

	/**
	 * 跳转到周报评价指标列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增周报评价指标页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑周报评价指标页面
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
	 * 跳转到查看周报评价指标页面
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
<?php
/**
 * @author Show
 * @Date 2013年6月6日 星期四 15:38:39
 * @version 1.0
 * @description:项目资源计划变更表控制层
 */
class controller_engineering_change_esmchangeres extends controller_base_action {

	function __construct() {
		$this->objName = "esmchangeres";
		$this->objPath = "engineering_change";
		parent :: __construct();
	}

	/**
	 * 跳转到项目资源计划变更表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增项目资源计划变更表页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑项目资源计划变更表页面
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
	 * 跳转到查看项目资源计划变更表页面
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
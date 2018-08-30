<?php
/**
 * @author Show
 * @Date 2012年11月27日 星期二 11:40:15
 * @version 1.0
 * @description:考核指标表控制层
 */
class controller_engineering_assess_esmassindex extends controller_base_action {

	function __construct() {
		$this->objName = "esmassindex";
		$this->objPath = "engineering_assess";
		parent :: __construct();
	}

	/**
	 * 跳转到考核指标表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增考核指标表页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑考核指标表页面
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
	 * 跳转到查看考核指标表页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	//ajax获取指标信息
	function c_ajaxGetIndex(){
		$indexIds = $_POST['indexIds'];
		$needIndexIds = $_POST['needIndexIds'];
		$indexArr = $this->service->getIndexs_d($indexIds);
		$indexStr = $this->service->initEdit_d($indexArr,$needIndexIds);
		echo util_jsonUtil::iconvGB2UTF($indexStr);
	}
}
?>
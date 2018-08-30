<?php
/**
 * @author Show
 * @Date 2012年11月27日 星期二 19:45:15
 * @version 1.0
 * @description:考核模板表控制层
 */
class controller_engineering_assess_esmasstemplate extends controller_base_action {

	function __construct() {
		$this->objName = "esmasstemplate";
		$this->objPath = "engineering_assess";
		parent :: __construct();
	}

	/**
	 * 跳转到考核模板表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增考核模板表页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑考核模板表页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('indexInfo',$this->service->initEdit_d($obj));
		$this->view('edit');
	}

	/**
	 * 跳转到查看考核模板表页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('indexInfo',$this->service->initDetail_d($obj));
		$this->view('view');
	}

	/**
	 * 工程考核设置页面
	 */
	function c_toAssessSetting(){
		$this->view('assesssetting');
	}
}
?>
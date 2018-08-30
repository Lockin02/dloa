<?php
/**
 * @author Show
 * @Date 2013年7月15日 15:15:40
 * @version 1.0
 * @description:回款奖惩期间控制层
 */
class controller_contract_config_periodconfig extends controller_base_action {

	function __construct() {
		$this->objName = "periodconfig";
		$this->objPath = "contract_config";
		parent :: __construct();
	}

	/**
	 * 跳转到回款奖惩期间列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增回款奖惩期间页面
	 */
	function c_toAdd() {
        $this->showDatadicts ( array ('periodType' => 'HKQJLX' ));
		$this->view('add');
	}

	/**
	 * 跳转到编辑回款奖惩期间页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
        $this->showDatadicts ( array ('periodType' => 'HKQJLX' ), $obj ['periodType']);
		$this->view('edit');
	}

	/**
	 * 跳转到查看回款奖惩期间页面
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
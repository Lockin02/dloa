<?php

/**
 * @author Show
 * @Date 2013年7月15日 11:31:24
 * @version 1.0
 * @description:付款条件设置控制层
 */
class controller_contract_config_payconfig extends controller_base_action {

	function __construct() {
		$this->objName = "payconfig";
		$this->objPath = "contract_config";
		parent :: __construct();
	}

	/**
	 * 跳转到付款条件设置列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增付款条件设置页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑付款条件设置页面
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
	 * 跳转到查看付款条件设置页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
        $this->assign('isNeedDate',$this->service->rtYesNo_d($obj['isNeedDate']));
        $this->assign('schePct',$this->service->rtYesNo_d($obj['schePct']));
		$this->view('view');
	}
}
?>
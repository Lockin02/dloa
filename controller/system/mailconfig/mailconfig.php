<?php
/**
 * @author Show
 * @Date 2013年7月11日 星期四 13:30:10
 * @version 1.0
 * @description:通用邮件配置控制层
 */
class controller_system_mailconfig_mailconfig extends controller_base_action {

	function __construct() {
		$this->objName = "mailconfig";
		$this->objPath = "system_mailconfig";
		parent :: __construct();
	}

	/**
	 * 跳转到通用邮件配置列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增通用邮件配置页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑通用邮件配置页面
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
	 * 跳转到查看通用邮件配置页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('isMain',$this->service->rtYesNo_d($obj['isMain']));
		$this->assign('isItem',$this->service->rtYesNo_d($obj['isItem']));
		$this->view('view');
	}
}
?>
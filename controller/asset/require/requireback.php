<?php

/**
 * @author Administrator
 * @Date 2013年8月1日 8:23:39
 * @version 1.0
 * @description:oa_asset_requireback控制层
 */
class controller_asset_require_requireback extends controller_base_action {

	function __construct() {
		$this->objName = "requireback";
		$this->objPath = "asset_require";
		parent :: __construct();
	}

	/*
	 * 跳转到oa_asset_requireback列表
	 */
	function c_page() {
		$this->view('list');
	}

	/*
	 * 跳转到oa_asset_requireback列表
	 */
	function c_pageByRequire() {
		$this->assign('requireId', $_GET['requireId']);
		$this->view('listbyrequire');
	}

	/**
		 * 跳转到新增oa_asset_requireback页面
		 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
		 * 跳转到编辑oa_asset_requireback页面
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
		 * 跳转到查看oa_asset_requireback页面
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
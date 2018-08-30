<?php

/**
 * @author Administrator
 * @Date 2013��8��1�� 8:23:39
 * @version 1.0
 * @description:oa_asset_requireback���Ʋ�
 */
class controller_asset_require_requireback extends controller_base_action {

	function __construct() {
		$this->objName = "requireback";
		$this->objPath = "asset_require";
		parent :: __construct();
	}

	/*
	 * ��ת��oa_asset_requireback�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/*
	 * ��ת��oa_asset_requireback�б�
	 */
	function c_pageByRequire() {
		$this->assign('requireId', $_GET['requireId']);
		$this->view('listbyrequire');
	}

	/**
		 * ��ת������oa_asset_requirebackҳ��
		 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
		 * ��ת���༭oa_asset_requirebackҳ��
		 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
		 * ��ת���鿴oa_asset_requirebackҳ��
		 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}
}
?>
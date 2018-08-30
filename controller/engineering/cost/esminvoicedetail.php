<?php

/**
 * @author Show
 * @Date 2012��7��31�� 20:24:45
 * @version 1.0
 * @description:���÷�Ʊ��ϸ���Ʋ�
 */
class controller_engineering_cost_esminvoicedetail extends controller_base_action {

	function __construct() {
		$this->objName = "esminvoicedetail";
		$this->objPath = "engineering_cost";
		parent :: __construct();
	}

	/**
	 * ��ת�����÷�Ʊ��ϸ�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	  * ��ת���������÷�Ʊ��ϸҳ��
	  */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	  * ��ת���༭���÷�Ʊ��ϸҳ��
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
	  * ��ת���鿴���÷�Ʊ��ϸҳ��
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
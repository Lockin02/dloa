<?php

/**
 * @author Show
 * @Date 2012��10��15�� ����һ 9:44:04
 * @version 1.0
 * @description:��Ʊ���ܵ����Ʋ�
 */
class controller_finance_expense_exbill extends controller_base_action {

	function __construct() {
		$this->objName = "exbill";
		$this->objPath = "finance_expense";
		parent :: __construct();
	}

	/**
	 * ��ת����Ʊ���ܵ��б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת��������Ʊ���ܵ�ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭��Ʊ���ܵ�ҳ��
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
	 * ��ת���鿴��Ʊ���ܵ�ҳ��
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
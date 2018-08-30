<?php

/**
 * @author Show
 * @Date 2012��10��11�� ������ 10:02:05
 * @version 1.0
 * @description:���û��ܱ���ϸ��ע��Ϣ���Ʋ�
 */
class controller_finance_expense_exsummarydetail extends controller_base_action {

	function __construct() {
		$this->objName = "exsummarydetail";
		$this->objPath = "finance_expense";
		parent :: __construct();
	}

	/**
	 * ��ת�����û��ܱ���ϸ��ע��Ϣ�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
		 * ��ת���������û��ܱ���ϸ��ע��Ϣҳ��
		 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
		 * ��ת���༭���û��ܱ���ϸ��ע��Ϣҳ��
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
		 * ��ת���鿴���û��ܱ���ϸ��ע��Ϣҳ��
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
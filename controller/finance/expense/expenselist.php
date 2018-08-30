<?php

/**
 * @author Show
 * @Date 2012��12��6�� ������ 14:29:37
 * @version 1.0
 * @description:�������뵥���Ʋ� DetailType
 1.���ű���
 2.(����)��Ŀ����
 3.�з���Ŀ����
 4.��ǰ����
 5.�ۺ����
 */
class controller_finance_expense_expenselist extends controller_base_action {
	function __construct() {
		$this->objName = "expenselist";
		$this->objPath = "finance_expense";
		parent :: __construct();
	}

	/**
	 * ��ת���������뵥�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת�������������뵥ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭�������뵥ҳ��
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
	 * ��ת���鿴�������뵥ҳ��
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
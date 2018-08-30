<?php
/**
 * @author Show
 * @Date 2013��7��5�� ������ 14:59:59
 * @version 1.0
 * @description:������Ʊ���÷�̯���Ʋ�
 */
class controller_finance_invother_invcostdetail extends controller_base_action {

	function __construct() {
		$this->objName = "invcostdetail";
		$this->objPath = "finance_invother";
		parent :: __construct();
	}

	/**
	 * ��ȡ������÷�̯���
	 */
	function c_getPayCost(){
		$sourceType = $_POST['sourceType'];
		$sourceCode = $_POST['sourceCode'];
		$rs = $this->service->getPayCost_d($sourceCode,$sourceType);
		echo util_jsonUtil::encode($rs);
	}

	/**
	 * ��ת��������Ʊ���÷�̯�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת������������Ʊ���÷�̯ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭������Ʊ���÷�̯ҳ��
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
	 * ��ת���鿴������Ʊ���÷�̯ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/**
	 * �鿴�����̯��Ϣ
	 */
	function c_listViewCost(){
		$otherId = $_POST['otherId'];//������ͬid

		$service = $this->service;
		$rows = $service->getListViewCost_d($otherId);
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}
?>
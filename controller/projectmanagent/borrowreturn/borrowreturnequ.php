<?php
/**
 * @author Administrator
 * @Date 2012-12-20 10:33:32
 * @version 1.0
 * @description:�����ù黹���ϴӱ���Ʋ�
 */
class controller_projectmanagent_borrowreturn_borrowreturnequ extends controller_base_action {

	function __construct() {
		$this->objName = "borrowreturnequ";
		$this->objPath = "projectmanagent_borrowreturn";
		parent :: __construct();
	}

	/**
	 * ��ת�������ù黹���ϴӱ��б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת�����������ù黹���ϴӱ�ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭�����ù黹���ϴӱ�ҳ��
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
	 * ��ת���鿴�����ù黹���ϴӱ�ҳ��
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
	 * ��ȡ�������ݷ���json
	 */
	function c_listJsonReturn() {
		$service = $this->service;
		$applyType = $_POST['applyType'];//��������
		//���������黹
		if($applyType == 'JYGHSQLX-01'){
			$_REQUEST['numSql'] = 'sql:and (c.disposeNumber < (c.qPassNum + c.qBackNum)) AND c.productId <> -1';
		}else{//�����������ʧ
			$_REQUEST['numSql'] = 'sql:and (c.disposeNumber < c.number) AND c.productId <> -1';
		}
		$service->getParam($_REQUEST);
		$rows = $service->listBySqlId('select_equinfo');
		//���ݹ���
		$rows = $service->filterArr_d($rows,$applyType);
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil :: encode($rows);
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJsonCompensate() {
		$service = $this->service;
		$applyType = $_POST['applyType'];//��������
		//���������黹
		if($applyType == 'JYGHSQLX-01'){
			$_REQUEST['numSql'] = 'sql:and (c.compensateNum < c.qBackNum)';
		}else{//�����������ʧ
			$_REQUEST['numSql'] = 'sql:and (c.compensateNum < c.number)';
		}
		$service->getParam($_REQUEST);
		$rows = $service->listBySqlId('select_compensate');
		//���ݹ���
		$rows = $service->filterArrCompensate_d($rows,$applyType);
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil :: encode($rows);
	}
}
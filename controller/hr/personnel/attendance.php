<?php

/**
 * @author Administrator
 * @Date 2012��5��31�� 17:03:17
 * @version 1.0
 * @description:������Ϣ���Ʋ�
 */
class controller_hr_personnel_attendance extends controller_base_action {

	function __construct() {
		$this->objName = "attendance";
		$this->objPath = "hr_personnel";
		parent :: __construct();
	}

	/*
	 * ��ת��������Ϣ�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/*
	 * ��ת��������Ϣ�б�
	 */
	function c_personlist() {
		$this->assign('userNo',$_GET['userNo']);
		$this->view('personlist');
	}
	/**
		 * ��ת������������Ϣҳ��
		 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ȡȨ��
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	/**
		 * ��ת���༭������Ϣҳ��
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
		 * ��ת���鿴������Ϣҳ��
		 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/*
	 * ��ת������ҳ��
	 */
	function c_toImport() {
		if (!isset ($this->service->this_limit['����Ȩ��'])) {
			showmsg('û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա');
		}
		$this->view('import');
	}
	/**
	 * Ա���̵���Ϣ����
	 */
	function c_import() {
		$objKeyArr = array (
			0 => 'userNo',
			1 => 'userName',
			2 => 'deptNameS',
			3 => 'deptNameT',
			4 => 'beginDate',
			5 => 'endDate',
			6 => 'days',
			7 => 'typeName',
			8 => 'docStatusName',
			9 => 'inputName',
			10 => 'inputNo'
		); //�ֶ�����
		$resultArr = $this->service->import_d($objKeyArr);
	}

}
?>
<?php

/**
 * @author Show
 * @Date 2011��12��27�� ���ڶ� 9:50:27
 * @version 1.0
 * @description:������Ϣ(oa_carrental_carinfo)���Ʋ� ����״̬ status
                                              0 ��Ч
                                              1 ʧЧ
 */
class controller_carrental_carinfo_carinfo extends controller_base_action {

	function __construct() {
		$this->objName = "carinfo";
		$this->objPath = "carrental_carinfo";
		parent :: __construct();
	}

	/*
	 * ��ת��������Ϣ
	 */
	function c_page() {
		$this->view('list');
	}

    /**
     * ���˲��Կ��б�
     */
	function c_myList(){
		$this->view('mylist');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_myPageJson() {
		$service = $this->service;

		$_POST['createId'] = $_SESSION['USER_ID'];
		$service->getParam ( $_POST );

		//$service->asc = false;
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ת������������Ϣ
	 */
	function c_toAdd() {
		$this->showDatadicts(array('carType' => 'GCZCCX'),null,true);
		$this->view('add');
	}

	//����
	function c_add($isEditInfo = false) {
		$id = $this->service->add_d($_POST[$this->objName], true);
		$msg = isset ($_POST["msg"]) ? $_POST["msg"] : '��ӳɹ���';
		if ($id) {
			msg($msg);
		}
	}

	/**
	 * ��ת���༭������Ϣ
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

		$this->showDatadicts(array('carType' => 'GCZCCX'),$obj['carType'],true);
		$this->view('edit');
	}

	/**
	 * ��ת���鿴������Ϣ
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('isSignCN',$this->service->rtYesOrNo($obj['isSign']));
		$this->view('view');
	}

	/**
	 * ��ת�����鿴Tabҳ
	 */
	function c_viewTab() {
		$this->permCheck(); //��ȫУ��
		$this->assign("id", $_GET['id']);
		$this->view('viewtab');
	}

	//�鿴ҳ�棿
	function c_toViewForCarrecord() {
		$this->view('view');
	}

	/**
	 * ��ת�鿴������ϢTab
	 */
	function c_toViewForCarinfo() {
		$this->assign("unitsId", $_GET['id']);
		$this->view('viewlist');
	}
}
?>
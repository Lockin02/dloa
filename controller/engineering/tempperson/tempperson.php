<?php

/**
 * @author Show
 * @Date 2012��7��17�� ���ڶ� 19:13:43
 * @version 1.0
 * @description:��Ƹ��Ա����Ʋ�
 */
class controller_engineering_tempperson_tempperson extends controller_base_action {

	function __construct() {
		$this->objName = "tempperson";
		$this->objPath = "engineering_tempperson";
		parent :: __construct();
	}

	/*
	 * ��ת����Ƹ��Ա���б�
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
	 * ��ת��������Ƹ��Ա��ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭��Ƹ��Ա��ҳ��
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
	 * ��ת���鿴��Ƹ��Ա��ҳ��
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
<?php

/**
 * @author Show
 * @Date 2012��8��24�� ������ 11:43:13
 * @version 1.0
 * @description:��ְ�ʸ���ί��ֱ���Ʋ�
 */
class controller_hr_certifyapply_scoredetail extends controller_base_action {

	function __construct() {
		$this->objName = "scoredetail";
		$this->objPath = "hr_certifyapply";
		parent :: __construct();
	}

	/**
	 * ��ת����ְ�ʸ���ί��ֱ��б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->asc = false;
		$rows = $service->list_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ��ת��������ְ�ʸ���ί��ֱ�ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭��ְ�ʸ���ί��ֱ�ҳ��
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
	 * ��ת���鿴��ְ�ʸ���ί��ֱ�ҳ��
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
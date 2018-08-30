<?php

/**
 * @author Show
 * @Date 2012��8��20�� ����һ 20:13:09
 * @version 1.0
 * @description:��ΪҪ�����ñ���Ʋ�
 */
class controller_hr_baseinfo_behamoduledetail extends controller_base_action {

	function __construct() {
		$this->objName = "behamoduledetail";
		$this->objPath = "hr_baseinfo";
		parent :: __construct();
	}

	/**
	 * ��ת����ΪҪ�����ñ��б�
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

	/********************* ��ɾ�Ĳ� ******************/

	/**
	 * ��ת��������ΪҪ�����ñ�ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭��ΪҪ�����ñ�ҳ��
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
	 * ��ת���鿴��ΪҪ�����ñ�ҳ��
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
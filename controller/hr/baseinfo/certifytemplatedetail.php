<?php
/**
 * @author Show
 * @Date 2012��8��21�� ���ڶ� 10:12:31
 * @version 1.0
 * @description:��ְ�ʸ�ģ����ϸ���Ʋ�
 */
class controller_hr_baseinfo_certifytemplatedetail extends controller_base_action {

	function __construct() {
		$this->objName = "certifytemplatedetail";
		$this->objPath = "hr_baseinfo";
		parent :: __construct();
	}

	/**
	 * ��ת����ְ�ʸ�ģ����ϸ�б�
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
	 * ��ת��������ְ�ʸ�ģ����ϸҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭��ְ�ʸ�ģ����ϸҳ��
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
	 * ��ת���鿴��ְ�ʸ�ģ����ϸҳ��
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
<?php
/**
 * @author Show
 * @Date 2012��11��28�� ������ 14:46:41
 * @version 1.0
 * @description:��ͬ����Ʊ�����Ʋ�
 */
class controller_contract_uninvoice_uninvoice extends controller_base_action {

	function __construct() {
		$this->objName = "uninvoice";
		$this->objPath = "contract_uninvoice";
		parent :: __construct();
	}

	/*
	 * ��ת����ͬ����Ʊ����б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��Ӧҵ���б�
	 */
	function c_toObjList(){
		$this->assignFunc($_GET);
		$this->view('listobj');
	}

	/**
	 * ��ת��������ͬ����Ʊ���ҳ��
	 */
	function c_toAdd() {
		$this->assignFunc($_GET);

		//��ȡĬ���ʼ�������
		$mailArr = $this->service->getMailInfo_d();
		$this->assignFunc($mailArr);

		//���ò���
		$newClass = $this->service->getClass($_GET['objType']);
		$initObj = new $newClass();
		//��ȡ��Ӧҵ����Ϣ
		$rs = $this->service->getObjInfo_d($_GET,$initObj);
		$this->assignFunc($rs);

		$this->view('add');
	}

	/**
	 * ��ת���༭��ͬ����Ʊ���ҳ��
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
	 * ��ת���鿴��ͬ����Ʊ���ҳ��
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
<?php

/**
 * @author show
 * @Date 2015��2��5�� 15:49:55
 * @version 1.0
 * @description:��Ŀ�رչ�����Ʋ�
 */
class controller_engineering_baseinfo_esmcloserule extends controller_base_action
{

	function __construct() {
		$this->objName = "esmcloserule";
		$this->objPath = "engineering_baseinfo";
		parent::__construct();
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listRuleJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$service->asc = false;
		$rows = $service->list_d('select_list');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil::encode($rows);
	}

	/**
	 * ��ת����Ŀ�رչ����б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת��������Ŀ�رչ���ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭��Ŀ�رչ���ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET ['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * ��ת���鿴��Ŀ�رչ���ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET ['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}
}
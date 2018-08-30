<?php

/**
 * @author Show
 * @Date 2013��7��15�� 11:31:24
 * @version 1.0
 * @description:�����������ÿ��Ʋ�
 */
class controller_contract_config_payconfig extends controller_base_action {

	function __construct() {
		$this->objName = "payconfig";
		$this->objPath = "contract_config";
		parent :: __construct();
	}

	/**
	 * ��ת���������������б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת������������������ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭������������ҳ��
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
	 * ��ת���鿴������������ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
        $this->assign('isNeedDate',$this->service->rtYesNo_d($obj['isNeedDate']));
        $this->assign('schePct',$this->service->rtYesNo_d($obj['schePct']));
		$this->view('view');
	}
}
?>
<?php

/**
 * @author Show
 * @Date 2012��8��30�� ������ 14:37:54
 * @version 1.0
 * @description:Ա��������ѵ�ƻ�ģ����Ʋ�
 */
class controller_hr_baseinfo_trialplantem extends controller_base_action {

	function __construct() {
		$this->objName = "trialplantem";
		$this->objPath = "hr_baseinfo";
		parent :: __construct();
	}

	/**
	 * ��ת��Ա��������ѵ�ƻ�ģ���б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת������Ա��������ѵ�ƻ�ģ��ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭Ա��������ѵ�ƻ�ģ��ҳ��
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
	 * ��ת���鿴Ա��������ѵ�ƻ�ģ��ҳ��
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
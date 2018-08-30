<?php
/**
 * @author Show
 * @Date 2012��12��10�� ����һ 14:20:22
 * @version 1.0
 * @description:��Ŀָ��ѡ����Ʋ�
 */
class controller_engineering_assess_esmassprooption extends controller_base_action {

	function __construct() {
		$this->objName = "esmassprooption";
		$this->objPath = "engineering_assess";
		parent :: __construct();
	}

	/**
	 * ��ת����Ŀָ��ѡ���б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת��������Ŀָ��ѡ��ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭��Ŀָ��ѡ��ҳ��
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
	 * ��ת���鿴��Ŀָ��ѡ��ҳ��
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
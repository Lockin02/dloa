<?php
/**
 * @author Show
 * @Date 2012��12��10�� ����һ 14:20:05
 * @version 1.0
 * @description:��Ŀָ����ϸ���Ʋ�
 */
class controller_engineering_assess_esmassproindex extends controller_base_action {

	function __construct() {
		$this->objName = "esmassproindex";
		$this->objPath = "engineering_assess";
		parent :: __construct();
	}

	/**
	 * ��ת����Ŀָ����ϸ�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת��������Ŀָ����ϸҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭��Ŀָ����ϸҳ��
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
	 * ��ת���鿴��Ŀָ����ϸҳ��
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
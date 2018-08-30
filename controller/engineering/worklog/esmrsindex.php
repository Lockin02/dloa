<?php
/**
 * @author Show
 * @Date 2012��12��3�� ����һ 10:42:07
 * @version 1.0
 * @description:�ܱ�����ָ����Ʋ�
 */
class controller_engineering_worklog_esmrsindex extends controller_base_action {

	function __construct() {
		$this->objName = "esmrsindex";
		$this->objPath = "engineering_worklog";
		parent :: __construct();
	}

	/**
	 * ��ת���ܱ�����ָ���б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת�������ܱ�����ָ��ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭�ܱ�����ָ��ҳ��
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
	 * ��ת���鿴�ܱ�����ָ��ҳ��
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
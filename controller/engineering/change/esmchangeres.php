<?php
/**
 * @author Show
 * @Date 2013��6��6�� ������ 15:38:39
 * @version 1.0
 * @description:��Ŀ��Դ�ƻ��������Ʋ�
 */
class controller_engineering_change_esmchangeres extends controller_base_action {

	function __construct() {
		$this->objName = "esmchangeres";
		$this->objPath = "engineering_change";
		parent :: __construct();
	}

	/**
	 * ��ת����Ŀ��Դ�ƻ�������б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת��������Ŀ��Դ�ƻ������ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭��Ŀ��Դ�ƻ������ҳ��
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
	 * ��ת���鿴��Ŀ��Դ�ƻ������ҳ��
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
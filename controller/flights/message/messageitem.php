<?php
/**
 * @author sony
 * @Date 2013��7��10�� 17:37:38
 * @version 1.0
 * @description:��ǩ�ӱ��ֶο��Ʋ�
 */
class controller_flights_message_messageitem extends controller_base_action {

	function __construct() {
		$this->objName = "messageitem";
		$this->objPath = "flights_message";
		parent :: __construct();
	}

	/**
	 * ��ת����ǩ�ӱ��ֶ��б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת��������ǩ�ӱ��ֶ�ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭��ǩ�ӱ��ֶ�ҳ��
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
	 * ��ת���鿴��ǩ�ӱ��ֶ�ҳ��
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
<?php
/**
 * @author Show
 * @Date 2013��5��20�� ����һ 11:55:49
 * @version 1.0
 * @description:�ʼ췽�����Ʋ�
 */
class controller_produce_quality_quaprogram extends controller_base_action {

	function __construct() {
		$this->objName = "quaprogram";
		$this->objPath = "produce_quality";
		parent :: __construct();
	}

	/*
	 * ��ת���ʼ췽���б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת�������ʼ췽��ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭�ʼ췽��ҳ��
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
	 * ��ת���鿴�ʼ췽��ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('isClose',$this->service->rtYesOrNo($obj['isClose']));

		$this->view('view');
	}
}
?>
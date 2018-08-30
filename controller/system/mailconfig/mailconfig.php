<?php
/**
 * @author Show
 * @Date 2013��7��11�� ������ 13:30:10
 * @version 1.0
 * @description:ͨ���ʼ����ÿ��Ʋ�
 */
class controller_system_mailconfig_mailconfig extends controller_base_action {

	function __construct() {
		$this->objName = "mailconfig";
		$this->objPath = "system_mailconfig";
		parent :: __construct();
	}

	/**
	 * ��ת��ͨ���ʼ������б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת������ͨ���ʼ�����ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭ͨ���ʼ�����ҳ��
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
	 * ��ת���鿴ͨ���ʼ�����ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('isMain',$this->service->rtYesNo_d($obj['isMain']));
		$this->assign('isItem',$this->service->rtYesNo_d($obj['isItem']));
		$this->view('view');
	}
}
?>
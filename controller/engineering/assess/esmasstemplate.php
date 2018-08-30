<?php
/**
 * @author Show
 * @Date 2012��11��27�� ���ڶ� 19:45:15
 * @version 1.0
 * @description:����ģ�����Ʋ�
 */
class controller_engineering_assess_esmasstemplate extends controller_base_action {

	function __construct() {
		$this->objName = "esmasstemplate";
		$this->objPath = "engineering_assess";
		parent :: __construct();
	}

	/**
	 * ��ת������ģ����б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת����������ģ���ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭����ģ���ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('indexInfo',$this->service->initEdit_d($obj));
		$this->view('edit');
	}

	/**
	 * ��ת���鿴����ģ���ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('indexInfo',$this->service->initDetail_d($obj));
		$this->view('view');
	}

	/**
	 * ���̿�������ҳ��
	 */
	function c_toAssessSetting(){
		$this->view('assesssetting');
	}
}
?>
<?php

/**
 * @author Show
 * @Date 2012��7��27�� ������ 16:23:53
 * @version 1.0
 * @description:��Ŀ�����Ա���Ʋ�
 */
class controller_engineering_activity_esmactmember extends controller_base_action {

	function __construct() {
		$this->objName = "esmactmember";
		$this->objPath = "engineering_activity";
		parent :: __construct();
	}

	/*
	 * ��ת����Ŀ�����Ա�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת��������Ŀ�����Աҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭��Ŀ�����Աҳ��
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
	 * ��ת���鿴��Ŀ�����Աҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/**
	 * ���������Ա
	 */
	function c_toEditMember(){
		$this->assignFunc($_GET);

		//��ȡ������Ϣ
		$esmactivityArr = $this->service->getActivity_d($_GET['activityId']);
		$this->assignFunc($esmactivityArr);
		$this->view('editmember');
	}

	/**
	 * ���������Ա
	 */
	function c_editMember(){
		$object = $_POST[$this->objName];
		$rs = $this->service->editMember_d($object);
		if($rs){
			msg('����ɹ�');
		}else{
			msg('����ʧ��');
		}
	}
}
?>
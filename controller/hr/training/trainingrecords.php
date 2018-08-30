<?php

/**
 * @author Show
 * @Date 2012��5��30�� ������ 14:02:31
 * @version 1.0
 * @description:��ѵ����-�γ���ϸ��¼���Ʋ�
 */
class controller_hr_training_trainingrecords extends controller_base_action {

	function __construct() {
		$this->objName = "trainingrecords";
		$this->objPath = "hr_training";
		parent :: __construct();
	}

	/*
	 * ��ת����ѵ����-�γ���ϸ��¼�б�
	 */
	function c_page() {
		$this->view('list');
	}
	/*
	 * ��ת����ѵ����-�γ�TAB
	 */
	function c_pageByCourse() {
		$this->assign('courseId',$_GET['courseId']);
		$this->view('listbycourse');
	}

	/*
	 * ��ת����ѵ����-����
	 */
	function c_pageByPerson() {
		$this->assign( 'userAccount',$_GET['userAccount'] );
		$this->assign( 'userNo',$_GET['userNo'] );
		$this->assign( 'userName',$_GET['userName'] );
		$this->view('listbyperson');
	}

	/**
	 * ��ת��������ѵ����-�γ���ϸ��¼ҳ��
	 */
	function c_toAdd() {
		$this->view('add',true);
	}

	/**
	 * ��ת���༭��ѵ����-�γ���ϸ��¼ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

		$this->showDatadicts(array('status' => 'HRPXZT'),$obj['status']);
		$this->showDatadicts(array('assessment' => 'HRPXKH'),$obj['assessment']);
		$this->showDatadicts(array('trainsType' => 'HRPXLX'),$obj['trainsType']);
		$this->showDatadicts(array('trainsMethodCode' => 'HRPXFS'),$obj['trainsMethodCode']);

		$this->view('edit',true);
	}

	/**
	 * ��ת���鿴��ѵ����-�γ���ϸ��¼ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('status',$this->getDataNameByCode($obj['status']));
		$this->assign('isInner',$this->service->rtIsInner_d($obj['isInner']));

		$this->assign('isUploadTA',$this->service->rtHandStatus_d($obj['isUploadTA']));
		$this->assign('isUploadTU',$this->service->rtHandStatus_d($obj['isUploadTU']));

		$this->view('view');
	}

	/**
	 * ��ȡȨ��
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	/******************* S ���뵼��ϵ�� ************************/
	/**
	 * ����excel
	 */
	function c_toExcelIn(){
		$this->display('excelin');
	}

	/**
	 * ����excel
	 */
	function c_excelIn(){
		$resultArr = $this->service->addExecelData_d ();

		$title = '��ѵ��¼�������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
	/******************* E ���뵼��ϵ�� ************************/
}
?>
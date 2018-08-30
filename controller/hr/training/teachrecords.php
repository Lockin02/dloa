<?php

/**
 * @author Show
 * @Date 2012��5��31�� ������ 10:13:30
 * @version 1.0
 * @description:��ѵ����-�ڿμ�¼���Ʋ�
 */
class controller_hr_training_teachrecords extends controller_base_action {

	function __construct() {
		$this->objName = "teachrecords";
		$this->objPath = "hr_training";
		parent :: __construct();
	}

	/*
	 * ��ת����ѵ����-�ڿμ�¼�б�
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

	/**
	 * ��ת��������ѵ����-�ڿμ�¼ҳ��
	 */
	function c_toAdd() {
		$this->view('add',true);
	}

	/**
	 * ��ת���༭��ѵ����-�ڿμ�¼ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);

		$this->showDatadicts(array('assessment' => 'HRPXKH'),$obj['assessment']);
		$this->showDatadicts(array('trainsType' => 'HRPXLX'),$obj['trainsType']);
		$this->showDatadicts(array('trainsMethodCode' => 'HRPXFS'),$obj['trainsMethodCode']);
		$this->view('edit',true);
	}

	/**
	 * ��ת���鿴��ѵ����-�ڿμ�¼ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$assessmentScore = sprintf("%.2f", $obj[assessmentScore]);
		$this->assign('assessmentScore',$assessmentScore);
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
		set_time_limit(0);
		$resultArr = $this->service->addExecelData_d ();

		$title = '�ڿμ�¼�������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
	/******************* E ���뵼��ϵ�� ************************/
}
?>
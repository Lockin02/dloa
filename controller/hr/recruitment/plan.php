<?php
/*
 * @author: zengq
 * Created on 2012-10-16
 *
 * @description:��Ƹ�ƻ� ���Ʋ�
 */
class controller_hr_recruitment_plan extends controller_base_action {

	function __construct() {
		$this->objName = "plan";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * ��ת����Ƹ�ƻ��б�
	 */
	function c_page() {

		$this->view('list');
	}
	/**
	 * ��ת�鿴��Ƹ�ƻ�ҳ��
	 */
	function c_view() {

		$this->view('view');
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
		$title = '��Ƹ�ƻ��������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
}
?>

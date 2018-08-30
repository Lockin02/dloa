<?php

/**
 * @author Show
 * @Date 2012��5��29�� ���ڶ� 9:24:35
 * @version 1.0
 * @description:��ѵ�γ̱���Ʋ�
 */
class controller_hr_training_course extends controller_base_action {

	function __construct() {
		$this->objName = "course";
		$this->objPath = "hr_training";
		parent :: __construct();
	}

	/*
	 * ��ת����ѵ�γ̱��б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת��������ѵ�γ̱�ҳ��
	 */
	function c_toAdd() {
		$this->showDatadicts(array('courseType' => 'HRPXLB'));
		$this->showDatadicts(array('status' => 'HRKCZT'));

		$this->view('add');
	}

	/*
	 * ��ת����ѵ�γ̱� �鿴TAB
	 */
	function c_viewTab() {
		$this->assign( 'id',$_GET['id'] );
		$this->view('viewtab');
	}

	/**
	 * ��ת���༭��ѵ�γ̱�ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->showDatadicts(array('courseType' => 'HRPXLB'),$obj['courseType']);
		$this->showDatadicts(array('status' => 'HRKCZT'),$obj['status']);

		$this->view('edit');
	}

	/**
	 * ��ת���鿴��ѵ�γ̱�ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('status',$this->getDataNameByCode($obj['status']));

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

		$title = '�γ���Ϣ�������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
	/******************* E ���뵼��ϵ�� ************************/
}
?>
<?php
/**
 * @author Show
 * @Date 2012��5��30�� ������ 9:56:29
 * @version 1.0
 * @description:��ѵ����-��ʦ������Ʋ�
 */
class controller_hr_training_teacher extends controller_base_action {

	function __construct() {
		$this->objName = "teacher";
		$this->objPath = "hr_training";
		parent :: __construct();
	}

	/*
	 * ��ת����ѵ����-��ʦ�����б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת��������ѵ����-��ʦ����ҳ��
	 */
	function c_toAdd() {
		$this->view('add',true);
	}

	/**
	 * ��ת���༭��ѵ����-��ʦ����ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			if ($key=="lecturerCategory"){
				if ($val=="��ѵʦ"){
					$this->assign("check1", "selected");
				}
				if ($val=="��ʱ��ʦ"){
					$this->assign("check2", "selected");
				}
				if ($val=="�ⲿ��ʦ"){
					$this->assign("check3", "selected");
				}
			}else{
			$this->assign($key, $val);
			}
		}
		$this->showDatadicts( array('level'=>'HRNSSJB'),$obj['levelId'],true);

		$this->view('edit',true);
	}

	/**
	 * ��ת���鿴��ѵ����-��ʦ����ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
//		$this->assign('isInnerCN',$this->service->rtYN_d($obj['isInner']));

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

		$title = '��ʦ��Ϣ�������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
	/******************* E ���뵼��ϵ�� ************************/
}
?>
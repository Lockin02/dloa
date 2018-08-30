<?php
class controller_rdproject_projecttype_projecttype extends controller_base_action{
	/**
	 * ���캯��
	 */
	function __construct(){
		$this->objName = "projecttype";
		$this->objPath = "rdproject_projecttype";
		parent::__construct();
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊ��ͨaction����-----------------------------------------------*
	 **************************************************************************************************/
	/*
	 * ��ת�����ҳ��
	 */
	function c_toAddProjectType(){
		$this->show->display($this->objPath . '_' . $this->objName . '-add');
	}

	/*
	 * ��Ŀ���͵ı��淽��
	 */
	function c_saveProjectType(){
		$typeObj = $_POST[$this->objName];
		$arr = $this->service->addProjectType_d($typeObj);
		if($arr){
			msg('�����Ŀ���ͳɹ�');
		}
	}

	/*
	 * ��ת����Ŀ���͵��޸�ҳ��
	 */
	function c_toEditProjectType(){
		$this->show->display($this->objPath . '_' . $this->objName . '-edit');
	}

	/*
	 * ��Ŀ���͵��޸ķ���
	 */
	function c_editProjectType(){
		$getName = $_POST[$this->objName];
		$arr = $this->service->editProjectType_d($getName,true);
		if($arr){
			msg('�༭��Ŀ���ͳɹ�');
		}
	}

	/*
	 * ��Ŀ���͵��б���ʾ����
	 */
	function c_showProjectType(){
		$service = $this->service;

		$auditArr = array(
			"createrId" => $_SESSION['USER_ID']
		);

		$rows = $service->page_d();

		$this->show->assign('projectTypelist',$service->showProjectType_d($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-list');
	}



}
?>

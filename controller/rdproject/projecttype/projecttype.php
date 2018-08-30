<?php
class controller_rdproject_projecttype_projecttype extends controller_base_action{
	/**
	 * 构造函数
	 */
	function __construct(){
		$this->objName = "projecttype";
		$this->objPath = "rdproject_projecttype";
		parent::__construct();
	}

	/***************************************************************************************************
	 * ------------------------------以下为普通action方法-----------------------------------------------*
	 **************************************************************************************************/
	/*
	 * 跳转到添加页面
	 */
	function c_toAddProjectType(){
		$this->show->display($this->objPath . '_' . $this->objName . '-add');
	}

	/*
	 * 项目类型的保存方法
	 */
	function c_saveProjectType(){
		$typeObj = $_POST[$this->objName];
		$arr = $this->service->addProjectType_d($typeObj);
		if($arr){
			msg('添加项目类型成功');
		}
	}

	/*
	 * 跳转到项目类型的修改页面
	 */
	function c_toEditProjectType(){
		$this->show->display($this->objPath . '_' . $this->objName . '-edit');
	}

	/*
	 * 项目类型的修改方法
	 */
	function c_editProjectType(){
		$getName = $_POST[$this->objName];
		$arr = $this->service->editProjectType_d($getName,true);
		if($arr){
			msg('编辑项目类型成功');
		}
	}

	/*
	 * 项目类型的列表显示方法
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

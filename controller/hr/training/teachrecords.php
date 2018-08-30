<?php

/**
 * @author Show
 * @Date 2012年5月31日 星期四 10:13:30
 * @version 1.0
 * @description:培训管理-授课记录控制层
 */
class controller_hr_training_teachrecords extends controller_base_action {

	function __construct() {
		$this->objName = "teachrecords";
		$this->objPath = "hr_training";
		parent :: __construct();
	}

	/*
	 * 跳转到培训管理-授课记录列表
	 */
	function c_page() {
		$this->view('list');
	}

	/*
	 * 跳转到培训管理-课程TAB
	 */
	function c_pageByCourse() {
		$this->assign('courseId',$_GET['courseId']);
		$this->view('listbycourse');
	}

	/**
	 * 跳转到新增培训管理-授课记录页面
	 */
	function c_toAdd() {
		$this->view('add',true);
	}

	/**
	 * 跳转到编辑培训管理-授课记录页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);

		$this->showDatadicts(array('assessment' => 'HRPXKH'),$obj['assessment']);
		$this->showDatadicts(array('trainsType' => 'HRPXLX'),$obj['trainsType']);
		$this->showDatadicts(array('trainsMethodCode' => 'HRPXFS'),$obj['trainsMethodCode']);
		$this->view('edit',true);
	}

	/**
	 * 跳转到查看培训管理-授课记录页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$assessmentScore = sprintf("%.2f", $obj[assessmentScore]);
		$this->assign('assessmentScore',$assessmentScore);
		$this->view('view');
	}

	/**
	 * 获取权限
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	/******************* S 导入导出系列 ************************/
	/**
	 * 导入excel
	 */
	function c_toExcelIn(){
		$this->display('excelin');
	}

	/**
	 * 导入excel
	 */
	function c_excelIn(){
		set_time_limit(0);
		$resultArr = $this->service->addExecelData_d ();

		$title = '授课记录导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
	/******************* E 导入导出系列 ************************/
}
?>
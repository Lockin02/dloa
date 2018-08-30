<?php
/*
 * @author: zengq
 * Created on 2012-10-16
 *
 * @description:招聘计划 控制层
 */
class controller_hr_recruitment_plan extends controller_base_action {

	function __construct() {
		$this->objName = "plan";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * 跳转到招聘计划列表
	 */
	function c_page() {

		$this->view('list');
	}
	/**
	 * 跳转查看招聘计划页面
	 */
	function c_view() {

		$this->view('view');
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
		$resultArr = $this->service->addExecelData_d ();
		$title = '招聘计划导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
}
?>

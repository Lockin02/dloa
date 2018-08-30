<?php

/**
 * @author Show
 * @Date 2012年5月29日 星期二 9:24:35
 * @version 1.0
 * @description:培训课程表控制层
 */
class controller_hr_training_course extends controller_base_action {

	function __construct() {
		$this->objName = "course";
		$this->objPath = "hr_training";
		parent :: __construct();
	}

	/*
	 * 跳转到培训课程表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增培训课程表页面
	 */
	function c_toAdd() {
		$this->showDatadicts(array('courseType' => 'HRPXLB'));
		$this->showDatadicts(array('status' => 'HRKCZT'));

		$this->view('add');
	}

	/*
	 * 跳转到培训课程表 查看TAB
	 */
	function c_viewTab() {
		$this->assign( 'id',$_GET['id'] );
		$this->view('viewtab');
	}

	/**
	 * 跳转到编辑培训课程表页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->showDatadicts(array('courseType' => 'HRPXLB'),$obj['courseType']);
		$this->showDatadicts(array('status' => 'HRKCZT'),$obj['status']);

		$this->view('edit');
	}

	/**
	 * 跳转到查看培训课程表页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('status',$this->getDataNameByCode($obj['status']));

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
		$resultArr = $this->service->addExecelData_d ();

		$title = '课程信息导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
	/******************* E 导入导出系列 ************************/
}
?>
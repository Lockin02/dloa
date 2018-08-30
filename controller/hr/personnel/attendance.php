<?php

/**
 * @author Administrator
 * @Date 2012年5月31日 17:03:17
 * @version 1.0
 * @description:考勤信息控制层
 */
class controller_hr_personnel_attendance extends controller_base_action {

	function __construct() {
		$this->objName = "attendance";
		$this->objPath = "hr_personnel";
		parent :: __construct();
	}

	/*
	 * 跳转到考勤信息列表
	 */
	function c_page() {
		$this->view('list');
	}

	/*
	 * 跳转到考勤信息列表
	 */
	function c_personlist() {
		$this->assign('userNo',$_GET['userNo']);
		$this->view('personlist');
	}
	/**
		 * 跳转到新增考勤信息页面
		 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 获取权限
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	/**
		 * 跳转到编辑考勤信息页面
		 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
		 * 跳转到查看考勤信息页面
		 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/*
	 * 跳转到导入页面
	 */
	function c_toImport() {
		if (!isset ($this->service->this_limit['导入权限'])) {
			showmsg('没有权限,需要开通权限请联系oa管理员');
		}
		$this->view('import');
	}
	/**
	 * 员工盘点信息导入
	 */
	function c_import() {
		$objKeyArr = array (
			0 => 'userNo',
			1 => 'userName',
			2 => 'deptNameS',
			3 => 'deptNameT',
			4 => 'beginDate',
			5 => 'endDate',
			6 => 'days',
			7 => 'typeName',
			8 => 'docStatusName',
			9 => 'inputName',
			10 => 'inputNo'
		); //字段数组
		$resultArr = $this->service->import_d($objKeyArr);
	}

}
?>
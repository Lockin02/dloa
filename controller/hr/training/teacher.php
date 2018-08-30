<?php
/**
 * @author Show
 * @Date 2012年5月30日 星期三 9:56:29
 * @version 1.0
 * @description:培训管理-讲师管理控制层
 */
class controller_hr_training_teacher extends controller_base_action {

	function __construct() {
		$this->objName = "teacher";
		$this->objPath = "hr_training";
		parent :: __construct();
	}

	/*
	 * 跳转到培训管理-讲师管理列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增培训管理-讲师管理页面
	 */
	function c_toAdd() {
		$this->view('add',true);
	}

	/**
	 * 跳转到编辑培训管理-讲师管理页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			if ($key=="lecturerCategory"){
				if ($val=="内训师"){
					$this->assign("check1", "selected");
				}
				if ($val=="临时讲师"){
					$this->assign("check2", "selected");
				}
				if ($val=="外部讲师"){
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
	 * 跳转到查看培训管理-讲师管理页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
//		$this->assign('isInnerCN',$this->service->rtYN_d($obj['isInner']));

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

		$title = '讲师信息导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
	/******************* E 导入导出系列 ************************/
}
?>
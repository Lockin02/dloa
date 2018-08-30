<?php

/**
 * @author Show
 * @Date 2012年7月27日 星期五 16:23:53
 * @version 1.0
 * @description:项目任务成员控制层
 */
class controller_engineering_activity_esmactmember extends controller_base_action {

	function __construct() {
		$this->objName = "esmactmember";
		$this->objPath = "engineering_activity";
		parent :: __construct();
	}

	/*
	 * 跳转到项目任务成员列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增项目任务成员页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑项目任务成员页面
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
	 * 跳转到查看项目任务成员页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/**
	 * 设置任务成员
	 */
	function c_toEditMember(){
		$this->assignFunc($_GET);

		//获取任务信息
		$esmactivityArr = $this->service->getActivity_d($_GET['activityId']);
		$this->assignFunc($esmactivityArr);
		$this->view('editmember');
	}

	/**
	 * 设置任务成员
	 */
	function c_editMember(){
		$object = $_POST[$this->objName];
		$rs = $this->service->editMember_d($object);
		if($rs){
			msg('保存成功');
		}else{
			msg('保存失败');
		}
	}
}
?>
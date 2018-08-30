<?php

/*
 * @author huangzhifan
 * @Date 2010-9-15
 * @copyright (c) YXKJ Company.
 * @description: 项目任务高级信息action
 *
 */
class controller_rdproject_task_tkfront extends controller_base_action {

	function __construct() {
		$this->objName = "tkfront";
		$this->objPath = "rdproject_task";
		parent :: __construct();
	}

	function c_toViewFrontDetail(){
		$this->show->assign("taskId",$_GET['taskId']);
		$this->show->assign("jsUrl",$_GET['jsUrl']);
		$frontTasks=$this->service->getFrontByTaskId_d($_GET['taskId']);
		$this->show->assign ( "tkfrontdetail", $this->service->showTkFrontDetail($frontTasks) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-detail' );

	}
}
?>

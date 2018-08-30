<?php

/*
 * @author huangzhifan
 * @Date 2010-9-15
 * @copyright (c) YXKJ Company.
 * @description: 项目任务高级信息action
 *
 */
class controller_rdproject_task_tkadvanced extends controller_base_action {

	function __construct() {
		$this->objName = "tkadvanced";
		$this->objPath = "rdproject_task";
		parent :: __construct();
	}

	/*
	 *从一键通详细信息页面跳转到高级信息编辑页面
	 */
	function c_toEditOnekeyAd() {
		$rows = $this->service->getTkAdByPTId($_GET['projectTaskId']);

		$this->show->assign("projectTaskId", $_GET['projectTaskId']);
		if (count($rows == 0))
			$this->show->display($this->objPath . '_' . 'rdtask-onekeyad-add');
		else {
			foreach ($rows as $key => $val) {
				$this->show->assign($key, $val);
			}
			$this->show->display($this->objPath . '_' . 'rdtask-onekeyad-edit');
		}
	}

}
?>

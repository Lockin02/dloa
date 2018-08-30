<?php
/**
 * @author Administrator
 * @Date 2012-11-14 11:06:15
 * @version 1.0
 * @description:项目设备任务单明细控制层
 */
class controller_engineering_resources_taskdetail extends controller_base_action {

	function __construct() {
		$this->objName = "taskdetail";
		$this->objPath = "engineering_resources";
		parent :: __construct();
	}
	
	/**
	 * 设备管理员确认发货分配数量从表
	 */
	function c_confirmTaskNumListJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->confirmTaskNumListJson_d ($_REQUEST['applyId']);
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}
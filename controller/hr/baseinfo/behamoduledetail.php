<?php

/**
 * @author Show
 * @Date 2012年8月20日 星期一 20:13:09
 * @version 1.0
 * @description:行为要项配置表控制层
 */
class controller_hr_baseinfo_behamoduledetail extends controller_base_action {

	function __construct() {
		$this->objName = "behamoduledetail";
		$this->objPath = "hr_baseinfo";
		parent :: __construct();
	}

	/**
	 * 跳转到行为要项配置表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->asc = false;
		$rows = $service->list_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/********************* 增删改查 ******************/

	/**
	 * 跳转到新增行为要项配置表页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑行为要项配置表页面
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
	 * 跳转到查看行为要项配置表页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}
}
?>
<?php

/**
 * @author Show
 * @Date 2012年11月27日 星期二 10:31:09
 * @version 1.0
 * @description:指标评价选项控制层
 */
class controller_engineering_assess_esmassoption extends controller_base_action {

	function __construct() {
		$this->objName = "esmassoption";
		$this->objPath = "engineering_assess";
		parent :: __construct();
	}

	/**
	 * 跳转到指标评价选项列表
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

	/**
	 * 跳转到新增指标评价选项页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑指标评价选项页面
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
	 * 跳转到查看指标评价选项页面
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
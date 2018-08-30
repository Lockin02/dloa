<?php

/**
 * @author Show
 * @Date 2012年8月30日 星期四 14:38:15
 * @version 1.0
 * @description:员工试用计划模板明细控制层
 */
class controller_hr_baseinfo_trialplantemdetail extends controller_base_action {

	function __construct() {
		$this->objName = "trialplantemdetail";
		$this->objPath = "hr_baseinfo";
		parent :: __construct();
	}

	/**
	 * 跳转到员工试用计划模板明细列表
	 */
	function c_page() {
		$this->assignFunc($_GET);
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
	 * 跳转到新增员工试用计划模板明细页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑员工试用计划模板明细页面
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
	 * 跳转到查看员工试用计划模板明细页面
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
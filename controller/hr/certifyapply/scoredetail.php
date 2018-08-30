<?php

/**
 * @author Show
 * @Date 2012年8月24日 星期五 11:43:13
 * @version 1.0
 * @description:任职资格评委打分表控制层
 */
class controller_hr_certifyapply_scoredetail extends controller_base_action {

	function __construct() {
		$this->objName = "scoredetail";
		$this->objPath = "hr_certifyapply";
		parent :: __construct();
	}

	/**
	 * 跳转到任职资格评委打分表列表
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
	 * 跳转到新增任职资格评委打分表页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑任职资格评委打分表页面
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
	 * 跳转到查看任职资格评委打分表页面
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
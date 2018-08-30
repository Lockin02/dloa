<?php

/**
 * @author Show
 * @Date 2012年7月17日 星期二 19:13:43
 * @version 1.0
 * @description:临聘人员库控制层
 */
class controller_engineering_tempperson_tempperson extends controller_base_action {

	function __construct() {
		$this->objName = "tempperson";
		$this->objPath = "engineering_tempperson";
		parent :: __construct();
	}

	/*
	 * 跳转到临聘人员库列表
	 */
	function c_page() {
		$this->view('list');
	}

    /**
     * 个人测试卡列表
     */
	function c_myList(){
		$this->view('mylist');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_myPageJson() {
		$service = $this->service;

		$_POST['createId'] = $_SESSION['USER_ID'];
		$service->getParam ( $_POST );

		//$service->asc = false;
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 跳转到新增临聘人员库页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑临聘人员库页面
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
	 * 跳转到查看临聘人员库页面
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
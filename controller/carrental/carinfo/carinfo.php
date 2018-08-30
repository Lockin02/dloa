<?php

/**
 * @author Show
 * @Date 2011年12月27日 星期二 9:50:27
 * @version 1.0
 * @description:车辆信息(oa_carrental_carinfo)控制层 车辆状态 status
                                              0 生效
                                              1 失效
 */
class controller_carrental_carinfo_carinfo extends controller_base_action {

	function __construct() {
		$this->objName = "carinfo";
		$this->objPath = "carrental_carinfo";
		parent :: __construct();
	}

	/*
	 * 跳转到车辆信息
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
	 * 跳转到新增车辆信息
	 */
	function c_toAdd() {
		$this->showDatadicts(array('carType' => 'GCZCCX'),null,true);
		$this->view('add');
	}

	//新增
	function c_add($isEditInfo = false) {
		$id = $this->service->add_d($_POST[$this->objName], true);
		$msg = isset ($_POST["msg"]) ? $_POST["msg"] : '添加成功！';
		if ($id) {
			msg($msg);
		}
	}

	/**
	 * 跳转到编辑车辆信息
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

		$this->showDatadicts(array('carType' => 'GCZCCX'),$obj['carType'],true);
		$this->view('edit');
	}

	/**
	 * 跳转到查看车辆信息
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('isSignCN',$this->service->rtYesOrNo($obj['isSign']));
		$this->view('view');
	}

	/**
	 * 跳转车辆查看Tab页
	 */
	function c_viewTab() {
		$this->permCheck(); //安全校验
		$this->assign("id", $_GET['id']);
		$this->view('viewtab');
	}

	//查看页面？
	function c_toViewForCarrecord() {
		$this->view('view');
	}

	/**
	 * 跳转查看车辆信息Tab
	 */
	function c_toViewForCarinfo() {
		$this->assign("unitsId", $_GET['id']);
		$this->view('viewlist');
	}
}
?>
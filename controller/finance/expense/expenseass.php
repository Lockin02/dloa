<?php

/**
 * @author Show
 * @Date 2012年9月28日 星期五 11:13:27
 * @version 1.0
 * @description:申请明细表控制层
 */
class controller_finance_expense_expenseass extends controller_base_action {

	function __construct() {
		$this->objName = "expenseass";
		$this->objPath = "finance_expense";
		parent :: __construct();
	}

	/**
	 * 跳转到申请明细表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 报销明细详情列表
	 */
	function c_detailList(){
		$this->assign('HeadID',$_GET['HeadID']);
		$this->view('listdetail');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_detailPageJson() {
		$service = $this->service;
;
		$service->getParam ( $_POST ); //设置前台获取的参数信息

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

	/******************* 增删查改 ***********************/

	/**
	 * 跳转到新增申请明细表页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑申请明细表页面
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
	 * 跳转到查看申请明细表页面
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
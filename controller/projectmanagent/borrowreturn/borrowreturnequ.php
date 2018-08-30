<?php
/**
 * @author Administrator
 * @Date 2012-12-20 10:33:32
 * @version 1.0
 * @description:借试用归还物料从表控制层
 */
class controller_projectmanagent_borrowreturn_borrowreturnequ extends controller_base_action {

	function __construct() {
		$this->objName = "borrowreturnequ";
		$this->objPath = "projectmanagent_borrowreturn";
		parent :: __construct();
	}

	/**
	 * 跳转到借试用归还物料从表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增借试用归还物料从表页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑借试用归还物料从表页面
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
	 * 跳转到查看借试用归还物料从表页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_listJsonReturn() {
		$service = $this->service;
		$applyType = $_POST['applyType'];//申请类型
		//如果是申请归还
		if($applyType == 'JYGHSQLX-01'){
			$_REQUEST['numSql'] = 'sql:and (c.disposeNumber < (c.qPassNum + c.qBackNum)) AND c.productId <> -1';
		}else{//如果是申请遗失
			$_REQUEST['numSql'] = 'sql:and (c.disposeNumber < c.number) AND c.productId <> -1';
		}
		$service->getParam($_REQUEST);
		$rows = $service->listBySqlId('select_equinfo');
		//数据过滤
		$rows = $service->filterArr_d($rows,$applyType);
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil :: encode($rows);
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_listJsonCompensate() {
		$service = $this->service;
		$applyType = $_POST['applyType'];//申请类型
		//如果是申请归还
		if($applyType == 'JYGHSQLX-01'){
			$_REQUEST['numSql'] = 'sql:and (c.compensateNum < c.qBackNum)';
		}else{//如果是申请遗失
			$_REQUEST['numSql'] = 'sql:and (c.compensateNum < c.number)';
		}
		$service->getParam($_REQUEST);
		$rows = $service->listBySqlId('select_compensate');
		//数据过滤
		$rows = $service->filterArrCompensate_d($rows,$applyType);
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil :: encode($rows);
	}
}
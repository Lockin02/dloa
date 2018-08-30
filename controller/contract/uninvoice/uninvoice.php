<?php
/**
 * @author Show
 * @Date 2012年11月28日 星期三 14:46:41
 * @version 1.0
 * @description:合同不开票金额控制层
 */
class controller_contract_uninvoice_uninvoice extends controller_base_action {

	function __construct() {
		$this->objName = "uninvoice";
		$this->objPath = "contract_uninvoice";
		parent :: __construct();
	}

	/*
	 * 跳转到合同不开票金额列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 对应业务列表
	 */
	function c_toObjList(){
		$this->assignFunc($_GET);
		$this->view('listobj');
	}

	/**
	 * 跳转到新增合同不开票金额页面
	 */
	function c_toAdd() {
		$this->assignFunc($_GET);

		//获取默认邮件发送人
		$mailArr = $this->service->getMailInfo_d();
		$this->assignFunc($mailArr);

		//调用策略
		$newClass = $this->service->getClass($_GET['objType']);
		$initObj = new $newClass();
		//获取对应业务信息
		$rs = $this->service->getObjInfo_d($_GET,$initObj);
		$this->assignFunc($rs);

		$this->view('add');
	}

	/**
	 * 跳转到编辑合同不开票金额页面
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
	 * 跳转到查看合同不开票金额页面
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
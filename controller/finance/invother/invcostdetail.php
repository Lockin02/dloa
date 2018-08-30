<?php
/**
 * @author Show
 * @Date 2013年7月5日 星期五 14:59:59
 * @version 1.0
 * @description:其他发票费用分摊控制层
 */
class controller_finance_invother_invcostdetail extends controller_base_action {

	function __construct() {
		$this->objName = "invcostdetail";
		$this->objPath = "finance_invother";
		parent :: __construct();
	}

	/**
	 * 获取付款费用分摊金额
	 */
	function c_getPayCost(){
		$sourceType = $_POST['sourceType'];
		$sourceCode = $_POST['sourceCode'];
		$rs = $this->service->getPayCost_d($sourceCode,$sourceType);
		echo util_jsonUtil::encode($rs);
	}

	/**
	 * 跳转到其他发票费用分摊列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增其他发票费用分摊页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑其他发票费用分摊页面
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
	 * 跳转到查看其他发票费用分摊页面
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
	 * 查看付款分摊信息
	 */
	function c_listViewCost(){
		$otherId = $_POST['otherId'];//其他合同id

		$service = $this->service;
		$rows = $service->getListViewCost_d($otherId);
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}
?>
<?php
/**
 * @author show
 * @Date 2013年10月10日 17:07:13
 * @version 1.0
 * @description:外包合同整包分包表控制层
 */
class controller_contract_outsourcing_projectrental extends controller_base_action {

	function __construct() {
		$this->objName = "projectrental";
		$this->objPath = "contract_outsourcing";
		parent :: __construct();
	}

	/**
	 * 跳转到外包合同整包分包表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增外包合同整包分包表页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑外包合同整包分包表页面
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
	 * 跳转到查看外包合同整包分包表页面
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
	 * 获取整包新增页面
	 */
	function c_getAddPage(){
		exit(util_jsonUtil::iconvGB2UTF($this->service->getAddPage_d()));
	}

	/**
	 * 获取整包编辑页面
	 */
	function c_getEditPage(){
		exit(util_jsonUtil::iconvGB2UTF($this->service->getEditPage_d($_POST['mainId'])));
	}

	/**
	 * 获取整包查看页面
	 */
	function c_getViewPage(){
		exit(util_jsonUtil::iconvGB2UTF($this->service->getViewPage_d($_POST['mainId'])));
	}

	/**
	 * 获取变更查看页面
	 */
	function c_getChangePage(){

	}
}
?>
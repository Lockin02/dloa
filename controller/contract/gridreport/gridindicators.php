<?php
/**
 * @author Michael
 * @Date 2014年12月1日 13:43:29
 * @version 1.0
 * @description:表格指标表控制层
 */
class controller_contract_gridreport_gridindicators extends controller_base_action {

	function __construct() {
		$this->objName = "gridindicators";
		$this->objPath = "contract_gridreport";
		parent::__construct ();
	}

	/**
	 * 跳转到表格指标表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增表格指标表页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑表格指标表页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ('edit');
	}

	/**
	 * 跳转到查看表格指标表页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}
}
?>
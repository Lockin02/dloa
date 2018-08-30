<?php
/**
 * @author Michael
 * @Date 2014年8月25日 11:04:57
 * @version 1.0
 * @description:生产任务配置控制层
 */
class controller_produce_task_taskconfig extends controller_base_action {

	function __construct() {
		$this->objName = "taskconfig";
		$this->objPath = "produce_task";
		parent::__construct ();
	 }

	/**
	 * 跳转到生产任务配置列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增生产任务配置页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑生产任务配置页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}

	/**
	 * 跳转到查看生产任务配置页面
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
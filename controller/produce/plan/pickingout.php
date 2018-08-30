<?php
/**
 * @author Michael
 * @Date 2015年1月14日 16:07:08
 * @version 1.0
 * @description:生产领料申请生产仓出库记录控制层
 */
class controller_produce_plan_pickingout extends controller_base_action {

	function __construct() {
		$this->objName = "pickingout";
		$this->objPath = "produce_plan";
		parent::__construct ();
	 }

	/**
	 * 跳转到生产领料申请生产仓出库记录列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 从生产领料跳转到生产仓出库记录
	 */
	function c_pagePick() {
		$this->assign('pickId' ,$_GET['pickId']);
		$this->view('list-pick');
	}

	/**
	 * 跳转到新增生产领料申请生产仓出库记录页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑生产领料申请生产仓出库记录页面
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
	 * 跳转到查看生产领料申请生产仓出库记录页面
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
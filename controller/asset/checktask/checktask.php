<?php

/**
 * 盘点任务控制层类
 *  @author chenzb
 */
class controller_asset_checktask_checktask extends controller_base_action {

	public $provArr;

	function __construct() {
		$this->objName = "checktask";
		$this->objPath = "asset_checktask";
		parent::__construct ();
	}
	/**
	 * 跳转到盘点任务信息列表
	 */
	function c_page() {
		$this->view ( 'list' );
	}

	/**
	 * 跳转到新增页面
	 */

	function c_toAdd() {

		$this->view ( 'add' );
	}

 /**
	 * 初始化对象
	 */
	function c_init() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}




}
?>
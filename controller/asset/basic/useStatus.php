<?php
/**
 *
 * 使用状态控制层类
 * @author chris
 *
 */
class controller_asset_basic_useStatus extends controller_base_action {

	function __construct() {
		$this->objName = "useStatus";
		$this->objPath = "asset_basic";
		parent::__construct ();
	}

	/*
	 * 跳转到使用状态
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		$this->assign ( 'createName', $_SESSION ['USERNAME'] );
		$this->assign ( 'createId', $_SESSION ['USER_ID'] );
		$this->assign ( 'createTime', date("Y-m-d") );
		$this->display ( 'add' );
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
			$this->display ( 'view' );
		} else {
			$this->assign ( 'updateName', $_SESSION ['USERNAME'] );
			$this->assign ( 'updateId', $_SESSION ['USER_ID'] );
			$this->assign ( 'updateTime', date("Y-m-d") );
			$this->display ( 'edit' );
		}
	}
}

?>
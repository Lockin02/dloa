<?php
/**
 * @author Michael
 * @Date 2014年7月25日 15:13:03
 * @version 1.0
 * @description:基础信息-工序控制层
 */
class controller_manufacture_basic_process extends controller_base_action {

	function __construct() {
		$this->objName = "process";
		$this->objPath = "manufacture_basic";
		parent::__construct ();
	 }

	/**
	 * 跳转到基础信息-工序列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增基础信息-工序页面
	 */
	function c_toAdd() {
		$this->assign('createName' ,$_SESSION['USERNAME']);
		$this->assign('createId' ,$_SESSION ['USER_ID']);
		$this->assign('createTime' ,date("Y-m-d H:i:s"));
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑基础信息-工序页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}

	/**
	 * 跳转到查看基础信息-工序页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

	/**
	 * ajax改变启用状态
	 */
	function c_ajaxEnable() {
		$ids = $_POST['ids'];
		$isEnable = util_jsonUtil::iconvUTF2GB ( $_POST['isEnable'] );
		if ($this->service->updateEnableByIds_d($ids ,$isEnable)) {
			echo 1;
		} else {
			echo 0;
		}
	}
}
?>
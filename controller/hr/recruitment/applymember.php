<?php
/**
 * @author Administrator
 * @Date 2012年7月14日 星期六 14:10:24
 * @version 1.0
 * @description:增员申请协助人控制层
 */
class controller_hr_recruitment_applymember extends controller_base_action {

	function __construct() {
		$this->objName = "applymember";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * 跳转到增员申请协助人列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增增员申请协助人页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	 
	/**
	 * 跳转到编辑增员申请协助人页面
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
	 * 跳转到查看增员申请协助人页面
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
<?php
/**
 * @author Administrator
 * @Date 2012年7月18日 星期三 15:11:15
 * @version 1.0
 * @description:面试官控制层
 */
class controller_hr_recruitment_interviewer extends controller_base_action {

	function __construct() {
		$this->objName = "interviewer";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * 跳转到面试官列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增面试官页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	 
	/**
	 * 跳转到编辑面试官页面
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
	 * 跳转到查看面试官页面
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
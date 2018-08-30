<?php
/**
 * @author Administrator
 * @Date 2012-07-19 11:15:08
 * @version 1.0
 * @description:职位申请表-工作经历控制层
 */
class controller_hr_recruitment_work extends controller_base_action {

	function __construct() {
		$this->objName = "work";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * 跳转到职位申请表-工作经历列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增职位申请表-工作经历页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	 
	/**
	 * 跳转到编辑职位申请表-工作经历页面
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
	 * 跳转到查看职位申请表-工作经历页面
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
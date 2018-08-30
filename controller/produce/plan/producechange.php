<?php
/**
 * @author Michael
 * @Date 2015年2月3日 17:11:23
 * @version 1.0
 * @description:生产领料申请退料记录控制层
 */
class controller_produce_plan_producechange extends controller_base_action {

	function __construct() {
		$this->objName = "producechange";
		$this->objPath = "produce_plan";
		parent::__construct ();
	 }

	/**
	 * 跳转到生产领料申请退料记录列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到生产领料申请退料记录列表
	 */
	function c_pageChange() {
		$this->assign ( 'planId', $_GET['planId'] );
		$this->view('changelist');
	}


	/**
	 * 跳转到新增生产领料申请退料记录页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑生产领料申请退料记录页面
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
	 * 跳转到查看生产领料申请退料记录页面
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
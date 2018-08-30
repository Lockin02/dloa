<?php
/**
 * @author Administrator
 * @Date 2011年12月3日 10:33:06
 * @version 1.0
 * @description:维修费用减免清单控制层 
 */
class controller_service_reduce_reduceitem extends controller_base_action {
	
	function __construct() {
		$this->objName = "reduceitem";
		$this->objPath = "service_reduce";
		parent::__construct ();
	}
	
	/*
	 * 跳转到维修费用减免清单列表
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * 跳转到新增维修费用减免清单页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	
	/**
	 * 跳转到编辑维修费用减免清单页面
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
	 * 跳转到查看维修费用减免清单页面
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
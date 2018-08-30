<?php
/**
 * @author huangzf
 * @Date 2012年4月20日 星期五 9:57:12
 * @version 1.0
 * @description:物料库存预警信息配置控制层 
 */
class controller_stock_productinfo_prostockwarn extends controller_base_action {
	
	function __construct() {
		$this->objName = "prostockwarn";
		$this->objPath = "stock_productinfo";
		parent::__construct ();
	}
	
	/*
	 * 跳转到物料库存预警信息配置列表
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * 跳转到新增物料库存预警信息配置页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	
	/**
	 * 跳转到编辑物料库存预警信息配置页面
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
	 * 跳转到查看物料库存预警信息配置页面
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
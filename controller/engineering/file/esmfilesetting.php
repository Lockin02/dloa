<?php
/**
 * @author tse
 * @Date 2014年1月4日 9:23:11
 * @version 1.0
 * @description:项目文档设置控制层 
 */
class controller_engineering_file_esmfilesetting extends controller_base_action {
	function __construct() {
		$this->objName = "esmfilesetting";
		$this->objPath = "engineering_file";
		parent::__construct ();
	}
	
	/**
	 * 跳转到项目文档设置列表
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * 跳转到新增项目文档设置页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	
	/**
	 * 跳转到编辑项目文档设置页面
	 */
	function c_toEdit() {
		$this->permCheck (); // 安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}
	
	/**
	 * 跳转到查看项目文档设置页面
	 */
	function c_toView() {
		$this->permCheck (); // 安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if($obj['isNeedUpload'] == 1)
			$isNeedUpload = '是';
		else 
			$isNeedUpload = '否';
		$this->assign ( 'isNeedUpload',$isNeedUpload);
		$this->view ( 'view' );
	}
}
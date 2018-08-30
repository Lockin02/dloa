<?php
/**
 * @author Administrator
 * @Date 2012年3月16日 11:56:51
 * @version 1.0
 * @description:关联出现条件控制层 
 */
class controller_goods_goods_asslist extends controller_base_action {

	function __construct() {
		$this->objName = "asslist";
		$this->objPath = "goods_goods";
		parent::__construct ();
	 }
    
	/*
	 * 跳转到关联出现条件列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增关联出现条件页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑关联出现条件页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * 跳转到查看关联出现条件页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }
 }
?>
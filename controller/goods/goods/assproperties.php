<?php
/**
 * @author Administrator
 * @Date 2012年3月1日 20:16:15
 * @version 1.0
 * @description:属性不可见性关系控制层 
 */
class controller_goods_goods_assproperties extends controller_base_action {

	function __construct() {
		$this->objName = "assproperties";
		$this->objPath = "goods_goods";
		parent::__construct ();
	 }
    
	/*
	 * 跳转到属性不可见性关系列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增属性不可见性关系页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑属性不可见性关系页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * 跳转到查看属性不可见性关系页面
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
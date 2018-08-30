<?php
/**
 * @author Administrator
 * @Date 2011年12月30日 11:43:26
 * @version 1.0
 * @description:BOM分录表控制层 
 */
class controller_produce_bom_bomitem extends controller_base_action {

	function __construct() {
		$this->objName = "bomitem";
		$this->objPath = "produce_bom";
		parent::__construct ();
	 }
    
	/*
	 * 跳转到BOM分录表列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增BOM分录表页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑BOM分录表页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * 跳转到查看BOM分录表页面
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
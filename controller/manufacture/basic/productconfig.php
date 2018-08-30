<?php
/**
 * @author Michael
 * @Date 2015年3月24日 9:40:31
 * @version 1.0
 * @description:基础物料配置表头控制层 
 */
class controller_manufacture_basic_productconfig extends controller_base_action {

	function __construct() {
		$this->objName = "productconfig";
		$this->objPath = "manufacture_basic";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到基础物料配置表头列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增基础物料配置表头页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑基础物料配置表头页面
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
	 * 跳转到查看基础物料配置表头页面
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
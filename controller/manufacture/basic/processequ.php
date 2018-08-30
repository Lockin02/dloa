<?php
/**
 * @author yxin1
 * @Date 2014年7月25日 15:20:28
 * @version 1.0
 * @description:基础信息-工序详情控制层 
 */
class controller_manufacture_basic_processequ extends controller_base_action {

	function __construct() {
		$this->objName = "processequ";
		$this->objPath = "manufacture_basic";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到基础信息-工序详情列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增基础信息-工序详情页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑基础信息-工序详情页面
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
	 * 跳转到查看基础信息-工序详情页面
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
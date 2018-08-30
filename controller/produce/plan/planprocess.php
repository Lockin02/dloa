<?php
/**
 * @author yxin1
 * @Date 2014年8月29日 14:39:36
 * @version 1.0
 * @description:生产计划工序控制层 
 */
class controller_produce_plan_planprocess extends controller_base_action {

	function __construct() {
		$this->objName = "planprocess";
		$this->objPath = "produce_plan";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到生产计划工序列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增生产计划工序页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑生产计划工序页面
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
	 * 跳转到查看生产计划工序页面
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
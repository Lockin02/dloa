<?php
/**
 * @author Administrator
 * @Date 2012-10-31 09:22:42
 * @version 1.0
 * @description:设备预算表从表控制层 
 */
class controller_equipment_budget_budgetinfo extends controller_base_action {

	function __construct() {
		$this->objName = "budgetinfo";
		$this->objPath = "equipment_budget";
		parent::__construct ();
	 }
    
	/*
	 * 跳转到设备预算表从表列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增设备预算表从表页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑设备预算表从表页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * 跳转到查看设备预算表从表页面
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
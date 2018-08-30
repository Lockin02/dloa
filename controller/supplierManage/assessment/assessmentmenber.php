<?php
/**
 * @author Administrator
 * @Date 2012年1月11日 16:58:32
 * @version 1.0
 * @description:评估人员控制层 
 */
class controller_supplierManage_assessment_assessmentmenber extends controller_base_action {

	function __construct() {
		$this->objName = "assessmentmenber";
		$this->objPath = "supplierManage_assessment";
		parent::__construct ();
	 }
    
	/*
	 * 跳转到评估人员列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增评估人员页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑评估人员页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * 跳转到查看评估人员页面
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
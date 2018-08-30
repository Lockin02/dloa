<?php
/**
 * @author Show
 * @Date 2011年12月27日 星期二 20:38:02
 * @version 1.0
 * @description:应付其他发票明细控制层 
 */
class controller_finance_invother_invotherdetail extends controller_base_action {

	function __construct() {
		$this->objName = "invotherdetail";
		$this->objPath = "finance_invother";
		parent::__construct ();
	 }
    
	/*
	 * 跳转到应付其他发票明细列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增应付其他发票明细页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑应付其他发票明细页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * 跳转到查看应付其他发票明细页面
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
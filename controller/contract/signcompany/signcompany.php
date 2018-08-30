<?php
/**
 * @author Show
 * @Date 2012年2月21日 星期二 15:37:22
 * @version 1.0
 * @description:签约公司控制层 
 */
class controller_contract_signcompany_signcompany extends controller_base_action {

	function __construct() {
		$this->objName = "signcompany";
		$this->objPath = "contract_signcompany";
		parent::__construct ();
	 }
    
	/*
	 * 跳转到签约公司列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增签约公司页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑签约公司页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * 跳转到查看签约公司页面
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
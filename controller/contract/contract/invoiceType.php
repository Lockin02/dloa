<?php
/**
 * @author Administrator
 * @Date 2014年2月11日 10:18:16
 * @version 1.0
 * @description:开票类型控制层 
 */
class controller_contract_contract_invoiceType extends controller_base_action {

	function __construct() {
		$this->objName = "invoiceType";
		$this->objPath = "contract_contract";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到开票类型列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增开票类型页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑开票类型页面
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
	 * 跳转到查看开票类型页面
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
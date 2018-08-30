<?php
/**
 * @author Administrator
 * @Date 2013年10月22日 星期二 14:22:04
 * @version 1.0
 * @description:供应商联系人控制层 
 */
class controller_outsourcing_supplier_linkman extends controller_base_action {

	function __construct() {
		$this->objName = "linkman";
		$this->objPath = "outsourcing_supplier";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到供应商联系人列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增供应商联系人页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑供应商联系人页面
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
	 * 跳转到查看供应商联系人页面
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
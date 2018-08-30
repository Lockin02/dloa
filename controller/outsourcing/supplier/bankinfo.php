<?php
/**
 * @author Administrator
 * @Date 2013年10月22日 星期二 14:22:33
 * @version 1.0
 * @description:供应商银行信息控制层 
 */
class controller_outsourcing_supplier_bankinfo extends controller_base_action {

	function __construct() {
		$this->objName = "bankinfo";
		$this->objPath = "outsourcing_supplier";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到供应商银行信息列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增供应商银行信息页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑供应商银行信息页面
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
	 * 跳转到查看供应商银行信息页面
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
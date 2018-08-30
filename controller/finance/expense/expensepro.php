<?php
/**
 * @author Show
 * @Date 2012年9月28日 星期五 11:07:32
 * @version 1.0
 * @description:报销申请费用明细(项目报销)控制层 
 */
class controller_finance_expense_expensepro extends controller_base_action {

	function __construct() {
		$this->objName = "expensepro";
		$this->objPath = "finance_expense";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到报销申请费用明细(项目报销)列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增报销申请费用明细(项目报销)页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑报销申请费用明细(项目报销)页面
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
	 * 跳转到查看报销申请费用明细(项目报销)页面
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
<?php
/**
 * @author phz
 * @Date 2014年1月18日 星期六 17:09:44
 * @version 1.0
 * @description:工单从表控制层 
 */
class controller_outsourcing_workorder_orderequ extends controller_base_action {

	function __construct() {
		$this->objName = "orderequ";
		$this->objPath = "outsourcing_workorder";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到工单从表列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增工单从表页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑工单从表页面
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
	 * 跳转到查看工单从表页面
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
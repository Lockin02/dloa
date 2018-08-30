<?php
/**
 * @author Show
 * @Date 2014年3月6日 星期四 10:13:09
 * @version 1.0
 * @description:租车同变更记录表控制层 
 */
class controller_outsourcing_contract_rentcar_changelog extends controller_base_action {

	function __construct() {
		$this->objName = "rentcar_changelog";
		$this->objPath = "outsourcing_contract";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到租车同变更记录表列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增租车同变更记录表页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑租车同变更记录表页面
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
	 * 跳转到查看租车同变更记录表页面
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
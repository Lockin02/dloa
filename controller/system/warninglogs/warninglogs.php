<?php
/**
 * @author Administrator
 * @Date 2014年3月17日 14:21:50
 * @version 1.0
 * @description:预警执行记录控制层 
 */
class controller_system_warninglogs_warninglogs extends controller_base_action {

	function __construct() {
		$this->objName = "warninglogs";
		$this->objPath = "system_warninglogs";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到预警执行记录列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增预警执行记录页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑预警执行记录页面
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
	 * 跳转到查看预警执行记录页面
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
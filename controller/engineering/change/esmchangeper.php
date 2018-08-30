<?php
/**
 * @author Show
 * @Date 2012年12月15日 星期六 15:21:37
 * @version 1.0
 * @description:项目变更人力预算控制层 
 */
class controller_engineering_change_esmchangeper extends controller_base_action {

	function __construct() {
		$this->objName = "esmchangeper";
		$this->objPath = "engineering_change";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到项目变更人力预算列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增项目变更人力预算页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑项目变更人力预算页面
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
	 * 跳转到查看项目变更人力预算页面
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
<?php
/**
 * @author Administrator
 * @Date 2012-09-08 14:24:37
 * @version 1.0
 * @description:商机团队成员权限表控制层 
 */
class controller_projectmanagent_chance_authorize extends controller_base_action {

	function __construct() {
		$this->objName = "authorize";
		$this->objPath = "projectmanagent_chance";
		parent::__construct ();
	 }
    
	/*
	 * 跳转到商机团队成员权限表列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增商机团队成员权限表页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑商机团队成员权限表页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * 跳转到查看商机团队成员权限表页面
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
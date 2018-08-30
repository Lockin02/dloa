<?php
/**
 * @author Administrator
 * @Date 2011年12月3日 10:34:08
 * @version 1.0
 * @description:设备更换清单控制层 
 */
class controller_service_change_changeitem extends controller_base_action {

	function __construct() {
		$this->objName = "changeitem";
		$this->objPath = "service_change";
		parent::__construct ();
	 }
    
	/*
	 * 跳转到设备更换清单列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增设备更换清单页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑设备更换清单页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * 跳转到查看设备更换清单页面
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
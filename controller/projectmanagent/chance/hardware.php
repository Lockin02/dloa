<?php
/**
 * @author Administrator
 * @Date 2013年5月29日 11:33:37
 * @version 1.0
 * @description:商机硬件设备表控制层 
 */
class controller_projectmanagent_chance_hardware extends controller_base_action {

	function __construct() {
		$this->objName = "hardware";
		$this->objPath = "projectmanagent_chance";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到商机硬件设备表列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增商机硬件设备表页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑商机硬件设备表页面
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
	 * 跳转到查看商机硬件设备表页面
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
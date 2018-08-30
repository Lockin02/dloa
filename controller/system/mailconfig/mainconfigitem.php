<?php
/**
 * @author Show
 * @Date 2013年7月11日 星期四 13:30:34
 * @version 1.0
 * @description:通用邮件配置从表控制层 
 */
class controller_system_mailconfig_mainconfigitem extends controller_base_action {

	function __construct() {
		$this->objName = "mainconfigitem";
		$this->objPath = "system_mailconfig";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到通用邮件配置从表列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增通用邮件配置从表页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑通用邮件配置从表页面
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
	 * 跳转到查看通用邮件配置从表页面
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
<?php
/**
 * @author Administrator
 * @Date 2014年3月17日 14:22:22
 * @version 1.0
 * @description:预警邮件通知情况控制层 
 */
class controller_system_warningmaillogs_warningmaillogs extends controller_base_action {

	function __construct() {
		$this->objName = "warningmaillogs";
		$this->objPath = "system_warningmaillogs";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到预警邮件通知情况列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增预警邮件通知情况页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑预警邮件通知情况页面
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
	 * 跳转到查看预警邮件通知情况页面
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
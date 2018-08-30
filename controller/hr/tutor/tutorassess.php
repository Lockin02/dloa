<?php
/**
 * @author Administrator
 * @Date 2012年8月22日 16:39:03
 * @version 1.0
 * @description:导师考核表控制层 
 */
class controller_hr_tutor_tutorassess extends controller_base_action {

	function __construct() {
		$this->objName = "tutorassess";
		$this->objPath = "hr_tutor";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到导师考核表列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增导师考核表页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑导师考核表页面
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
	 * 跳转到查看导师考核表页面
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
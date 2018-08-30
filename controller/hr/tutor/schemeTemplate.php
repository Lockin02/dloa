<?php
/**
 * @author Administrator
 * @Date 2012年8月22日 19:39:28
 * @version 1.0
 * @description:导师考核表模板控制层 
 */
class controller_hr_tutor_schemeTemplate extends controller_base_action {

	function __construct() {
		$this->objName = "schemeTemplate";
		$this->objPath = "hr_tutor";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到导师考核表模板列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增导师考核表模板页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑导师考核表模板页面
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
	 * 跳转到查看导师考核表模板页面
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
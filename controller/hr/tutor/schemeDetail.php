<?php
/**
 * @author Administrator
 * @Date 2012年10月7日 星期日 15:16:42
 * @version 1.0
 * @description:导师考核模板明细控制层 
 */
class controller_hr_tutor_schemeDetail extends controller_base_action {

	function __construct() {
		$this->objName = "schemeDetail";
		$this->objPath = "hr_tutor";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到导师考核模板明细列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增导师考核模板明细页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑导师考核模板明细页面
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
	 * 跳转到查看导师考核模板明细页面
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
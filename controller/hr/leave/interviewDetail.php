<?php
/**
 * @author Administrator
 * @Date 2012年10月29日 星期一 15:17:23
 * @version 1.0
 * @description:离职--面谈记录表详细控制层 
 */
class controller_hr_leave_interviewDetail extends controller_base_action {

	function __construct() {
		$this->objName = "interviewDetail";
		$this->objPath = "hr_leave";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到离职--面谈记录表详细列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增离职--面谈记录表详细页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑离职--面谈记录表详细页面
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
	 * 跳转到查看离职--面谈记录表详细页面
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
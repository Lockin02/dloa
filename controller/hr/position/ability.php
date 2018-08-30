<?php
/**
 * @author Administrator
 * @Date 2012年7月9日 星期一 14:16:48
 * @version 1.0
 * @description:职位能力要求控制层 
 */
class controller_hr_position_ability extends controller_base_action {

	function __construct() {
		$this->objName = "ability";
		$this->objPath = "hr_position";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到职位能力要求列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增职位能力要求页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑职位能力要求页面
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
	 * 跳转到查看职位能力要求页面
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
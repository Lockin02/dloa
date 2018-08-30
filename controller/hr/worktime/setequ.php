<?php
/**
 * @author yxin1
 * @Date 2014年4月24日 9:53:16
 * @version 1.0
 * @description:法定节假日详细控制层 
 */
class controller_hr_worktime_setequ extends controller_base_action {

	function __construct() {
		$this->objName = "setequ";
		$this->objPath = "hr_worktime";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到法定节假日详细列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增法定节假日详细页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑法定节假日详细页面
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
	 * 跳转到查看法定节假日详细页面
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
<?php
/**
 * @author Show
 * @Date 2012年8月20日 星期一 20:12:40
 * @version 1.0
 * @description:行为模块配置控制层 
 */
class controller_hr_baseinfo_behamodule extends controller_base_action {

	function __construct() {
		$this->objName = "behamodule";
		$this->objPath = "hr_baseinfo";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到行为模块配置列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增行为模块配置页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑行为模块配置页面
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
	 * 跳转到查看行为模块配置页面
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
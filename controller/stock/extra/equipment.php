<?php
/**
 * @author Administrator
 * @Date 2012年7月11日 星期三 14:18:58
 * @version 1.0
 * @description:常用设备基本信息控制层 
 */
class controller_stock_extra_equipment extends controller_base_action {

	function __construct() {
		$this->objName = "equipment";
		$this->objPath = "stock_extra";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到常用设备基本信息列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增常用设备基本信息页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑常用设备基本信息页面
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
	 * 跳转到查看常用设备基本信息页面
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
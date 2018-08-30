<?php
/**
 * @author Administrator
 * @Date 2012年8月30日 19:17:07
 * @version 1.0
 * @description:盘点总结属性控制层 
 */
class controller_hr_inventory_templatesummary extends controller_base_action {

	function __construct() {
		$this->objName = "templatesummary";
		$this->objPath = "hr_inventory";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到盘点总结属性列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增盘点总结属性页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑盘点总结属性页面
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
	 * 跳转到查看盘点总结属性页面
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
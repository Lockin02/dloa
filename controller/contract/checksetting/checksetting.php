<?php
/**
 * @author tse
 * @Date 2014年4月1日 10:47:06
 * @version 1.0
 * @description:验收管理设置控制层 
 */
class controller_contract_checksetting_checksetting extends controller_base_action {

	function __construct() {
		$this->objName = "checksetting";
		$this->objPath = "contract_checksetting";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到验收管理设置列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增验收管理设置页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑验收管理设置页面
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
	 * 跳转到查看验收管理设置页面
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
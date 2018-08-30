<?php
/**
 * @author Administrator
 * @Date 2013年10月28日 星期一 19:56:25
 * @version 1.0
 * @description:等级变更记录控制层
 */
class controller_outsourcing_supplier_changeInfo extends controller_base_action {

	function __construct() {
		$this->objName = "changeInfo";
		$this->objPath = "outsourcing_supplier";
		parent::__construct ();
	 }

	/**
	 * 跳转到等级变更记录列表
	 */
    function c_page() {
	 $suppId=isset($_GET ['suppId'])?$_GET ['suppId']:'';
	 $this->assign('suppId',$suppId);
      $this->view('list');
    }

   /**
	 * 跳转到新增等级变更记录页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑等级变更记录页面
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
	 * 跳转到查看等级变更记录页面
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
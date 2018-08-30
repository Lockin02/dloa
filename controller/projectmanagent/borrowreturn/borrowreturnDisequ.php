<?php
/**
 * @author Administrator
 * @Date 2012-12-20 10:33:32
 * @version 1.0
 * @description:借试用归还物料从表控制层
 */
class controller_projectmanagent_borrowreturn_borrowreturnDisequ extends controller_base_action {

	function __construct() {
		$this->objName = "borrowreturnDisequ";
		$this->objPath = "projectmanagent_borrowreturn";
		parent::__construct ();
	 }

	/**
	 * 跳转到借试用归还物料从表列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增借试用归还物料从表页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑借试用归还物料从表页面
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
	 * 跳转到查看借试用归还物料从表页面
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
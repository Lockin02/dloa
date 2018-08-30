<?php
/**
 * @author Michael
 * @Date 2014年10月8日 19:50:39
 * @version 1.0
 * @description:租车登记合同附加费控制层
 */
class controller_outsourcing_vehicle_registerfee extends controller_base_action {

	function __construct() {
		$this->objName = "registerfee";
		$this->objPath = "outsourcing_vehicle";
		parent::__construct ();
	 }

	/**
	 * 跳转到租车登记合同附加费列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增租车登记合同附加费页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑租车登记合同附加费页面
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
	 * 跳转到查看租车登记合同附加费页面
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
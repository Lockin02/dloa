<?php
/**
 * @author Michael
 * @Date 2014年3月24日 星期一 14:50:04
 * @version 1.0
 * @description:租车合同租赁车辆信息控制层
 */
class controller_outsourcing_contract_vehicle extends controller_base_action {

	function __construct() {
		$this->objName = "vehicle";
		$this->objPath = "outsourcing_contract";
		parent::__construct ();
	 }

	/**
	 * 跳转到租车合同租赁车辆信息列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增租车合同租赁车辆信息页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑租车合同租赁车辆信息页面
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
	 * 跳转到查看租车合同租赁车辆信息页面
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
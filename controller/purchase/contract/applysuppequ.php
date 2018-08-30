<?php
/**
 * @author Administrator
 * @Date 2012年12月14日 星期五 15:18:00
 * @version 1.0
 * @description:订单供应商_报价单物料清单控制层
 */
class controller_purchase_contract_applysuppequ extends controller_base_action {

	function __construct() {
		$this->objName = "applysuppequ";
		$this->objPath = "purchase_contract";
		parent::__construct ();
	 }

	/**
	 * 跳转到订单供应商_报价单物料清单列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增订单供应商_报价单物料清单页面
	 */
	function c_toAdd() {
		$this->view ( 'add');
	}

   /**
	 * 跳转到编辑订单供应商_报价单物料清单页面
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
	 * 跳转到查看订单供应商_报价单物料清单页面
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
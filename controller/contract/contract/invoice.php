<?php
/**
 * @author Liub
 * @Date 2012年3月8日 14:14:51
 * @version 1.0
 * @description:合同开票计划控制层
 */
class controller_contract_contract_invoice extends controller_base_action {

	function __construct() {
		$this->objName = "invoice";
		$this->objPath = "contract_contract";
		parent::__construct ();
	 }

	/*
	 * 跳转到合同开票计划列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增合同开票计划页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑合同开票计划页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * 跳转到查看合同开票计划页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }
 }
?>
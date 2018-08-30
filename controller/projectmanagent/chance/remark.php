<?php
/**
 * @author Administrator
 * @Date 2012-10-24 15:52:02
 * @version 1.0
 * @description:合同信息备注控制层
 */
class controller_projectmanagent_chance_remark  extends controller_base_action {

	function __construct() {
		$this->objName = "remark";
		$this->objPath = "projectmanagent_chance";
		parent::__construct ();
	 }

	/*
	 * 跳转到合同信息备注列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增合同信息备注页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑合同信息备注页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * 跳转到查看合同信息备注页面
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
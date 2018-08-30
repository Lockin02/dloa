<?php
/**
 * @author liub
 * @Date 2012-04-07 14:03:01
 * @version 1.0
 * @description:换货产品清单控制层
 */
class controller_projectmanagent_exchange_exchangeproduct extends controller_base_action {

	function __construct() {
		$this->objName = "exchangeproduct";
		$this->objPath = "projectmanagent_exchange";
		parent::__construct ();
	 }

	/*
	 * 跳转到换货产品清单列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增换货产品清单页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑换货产品清单页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * 跳转到查看换货产品清单页面
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
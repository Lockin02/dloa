<?php
/**
 * @author liub
 * @Date 2012-04-07 14:03:01
 * @version 1.0
 * @description:������Ʒ�嵥���Ʋ�
 */
class controller_projectmanagent_exchange_exchangeproduct extends controller_base_action {

	function __construct() {
		$this->objName = "exchangeproduct";
		$this->objPath = "projectmanagent_exchange";
		parent::__construct ();
	 }

	/*
	 * ��ת��������Ʒ�嵥�б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת������������Ʒ�嵥ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭������Ʒ�嵥ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴������Ʒ�嵥ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }
 }
?>
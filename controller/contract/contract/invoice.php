<?php
/**
 * @author Liub
 * @Date 2012��3��8�� 14:14:51
 * @version 1.0
 * @description:��ͬ��Ʊ�ƻ����Ʋ�
 */
class controller_contract_contract_invoice extends controller_base_action {

	function __construct() {
		$this->objName = "invoice";
		$this->objPath = "contract_contract";
		parent::__construct ();
	 }

	/*
	 * ��ת����ͬ��Ʊ�ƻ��б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת��������ͬ��Ʊ�ƻ�ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭��ͬ��Ʊ�ƻ�ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴��ͬ��Ʊ�ƻ�ҳ��
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
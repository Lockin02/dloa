<?php
/**
 * @author Show
 * @Date 2012��2��21�� ���ڶ� 15:37:22
 * @version 1.0
 * @description:ǩԼ��˾���Ʋ� 
 */
class controller_contract_signcompany_signcompany extends controller_base_action {

	function __construct() {
		$this->objName = "signcompany";
		$this->objPath = "contract_signcompany";
		parent::__construct ();
	 }
    
	/*
	 * ��ת��ǩԼ��˾�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת������ǩԼ��˾ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭ǩԼ��˾ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * ��ת���鿴ǩԼ��˾ҳ��
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
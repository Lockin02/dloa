<?php
/**
 * @author Administrator
 * @Date 2012��3��8�� 14:13:18
 * @version 1.0
 * @description:��ͬ��ѵ�ƻ����Ʋ� 
 */
class controller_contract_contract_trainingplan extends controller_base_action {

	function __construct() {
		$this->objName = "trainingplan";
		$this->objPath = "contract_contract";
		parent::__construct ();
	 }
    
	/*
	 * ��ת����ͬ��ѵ�ƻ��б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת��������ͬ��ѵ�ƻ�ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭��ͬ��ѵ�ƻ�ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * ��ת���鿴��ͬ��ѵ�ƻ�ҳ��
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
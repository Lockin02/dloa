<?php
/**
 * @author Administrator
 * @Date 2012��3��16�� 11:56:51
 * @version 1.0
 * @description:���������������Ʋ� 
 */
class controller_goods_goods_asslist extends controller_base_action {

	function __construct() {
		$this->objName = "asslist";
		$this->objPath = "goods_goods";
		parent::__construct ();
	 }
    
	/*
	 * ��ת���������������б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת������������������ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭������������ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * ��ת���鿴������������ҳ��
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
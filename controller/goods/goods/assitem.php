<?php
/**
 * @author Administrator
 * @Date 2012��3��1�� 20:16:01
 * @version 1.0
 * @description:���������ϵ���Ʋ� 
 */
class controller_goods_goods_assitem extends controller_base_action {

	function __construct() {
		$this->objName = "assitem";
		$this->objPath = "goods_goods";
		parent::__construct ();
	 }
    
	/*
	 * ��ת�����������ϵ�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת���������������ϵҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭���������ϵҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * ��ת���鿴���������ϵҳ��
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
<?php
/**
 * @author Administrator
 * @Date 2012-09-08 14:24:37
 * @version 1.0
 * @description:�̻��Ŷӳ�ԱȨ�ޱ���Ʋ� 
 */
class controller_projectmanagent_chance_authorize extends controller_base_action {

	function __construct() {
		$this->objName = "authorize";
		$this->objPath = "projectmanagent_chance";
		parent::__construct ();
	 }
    
	/*
	 * ��ת���̻��Ŷӳ�ԱȨ�ޱ��б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת�������̻��Ŷӳ�ԱȨ�ޱ�ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭�̻��Ŷӳ�ԱȨ�ޱ�ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * ��ת���鿴�̻��Ŷӳ�ԱȨ�ޱ�ҳ��
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
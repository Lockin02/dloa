<?php
/**
 * @author Administrator
 * @Date 2012-11-08 19:21:39
 * @version 1.0
 * @description:�̻��ر���Ϣ���Ʋ� 
 */
class controller_projectmanagent_chance_close extends controller_base_action {

	function __construct() {
		$this->objName = "close";
		$this->objPath = "projectmanagent_chance";
		parent::__construct ();
	 }
    
	/*
	 * ��ת���̻��ر���Ϣ�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת�������̻��ر���Ϣҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭�̻��ر���Ϣҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * ��ת���鿴�̻��ر���Ϣҳ��
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
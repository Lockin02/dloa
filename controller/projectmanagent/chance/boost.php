<?php
/**
 * @author Administrator
 * @Date 2012-08-03 14:08:32
 * @version 1.0
 * @description:�̻��ƽ���Ϣ���Ʋ�
 */
class controller_projectmanagent_chance_boost extends controller_base_action {

	function __construct() {
		$this->objName = "boost";
		$this->objPath = "projectmanagent_chance";
		parent::__construct ();
	 }

	/*
	 * ��ת���̻��ƽ���Ϣ�б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת�������̻��ƽ���Ϣҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭�̻��ƽ���Ϣҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴�̻��ƽ���Ϣҳ��
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
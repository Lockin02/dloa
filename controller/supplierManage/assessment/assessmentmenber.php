<?php
/**
 * @author Administrator
 * @Date 2012��1��11�� 16:58:32
 * @version 1.0
 * @description:������Ա���Ʋ� 
 */
class controller_supplierManage_assessment_assessmentmenber extends controller_base_action {

	function __construct() {
		$this->objName = "assessmentmenber";
		$this->objPath = "supplierManage_assessment";
		parent::__construct ();
	 }
    
	/*
	 * ��ת��������Ա�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת������������Աҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭������Աҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * ��ת���鿴������Աҳ��
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
<?php
/**
 * @author Administrator
 * @Date 2012-10-29 14:47:08
 * @version 1.0
 * @description:�豸������ϸ���Ʋ� 
 */
class controller_equipment_budget_deployinfo extends controller_base_action {

	function __construct() {
		$this->objName = "deployinfo";
		$this->objPath = "equipment_budget";
		parent::__construct ();
	 }
    
	/*
	 * ��ת���豸������ϸ�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת�������豸������ϸҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭�豸������ϸҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * ��ת���鿴�豸������ϸҳ��
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
<?php
/**
 * @author Administrator
 * @Date 2012-10-31 09:22:42
 * @version 1.0
 * @description:�豸Ԥ���ӱ���Ʋ� 
 */
class controller_equipment_budget_budgetinfo extends controller_base_action {

	function __construct() {
		$this->objName = "budgetinfo";
		$this->objPath = "equipment_budget";
		parent::__construct ();
	 }
    
	/*
	 * ��ת���豸Ԥ���ӱ��б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת�������豸Ԥ���ӱ�ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭�豸Ԥ���ӱ�ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * ��ת���鿴�豸Ԥ���ӱ�ҳ��
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
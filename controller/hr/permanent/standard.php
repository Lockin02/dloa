<?php
/**
 * @author jianjungki
 * @Date 2012��8��6�� 14:33:32
 * @version 1.0
 * @description:Ա��������Ŀ���Ʋ� 
 */
class controller_hr_permanent_standard extends controller_base_action {

	function __construct() {
		$this->objName = "standard";
		$this->objPath = "hr_permanent";
		parent::__construct ();
	 }
    
	/*
	 * ��ת��Ա��������Ŀ�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת������Ա��������Ŀҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭Ա��������Ŀҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * ��ת���鿴Ա��������Ŀҳ��
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
<?php
/**
 * @author yxin1
 * @Date 2014��7��25�� 15:20:28
 * @version 1.0
 * @description:������Ϣ-����������Ʋ� 
 */
class controller_manufacture_basic_processequ extends controller_base_action {

	function __construct() {
		$this->objName = "processequ";
		$this->objPath = "manufacture_basic";
		parent::__construct ();
	 }
    
	/**
	 * ��ת��������Ϣ-���������б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת������������Ϣ-��������ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭������Ϣ-��������ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}  
      $this->view ( 'edit');
   }
   
   /**
	 * ��ת���鿴������Ϣ-��������ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }
 }
?>
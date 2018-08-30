<?php
/**
 * @author Michael
 * @Date 2015��3��24�� 9:40:31
 * @version 1.0
 * @description:�����������ñ�ͷ���Ʋ� 
 */
class controller_manufacture_basic_productconfig extends controller_base_action {

	function __construct() {
		$this->objName = "productconfig";
		$this->objPath = "manufacture_basic";
		parent::__construct ();
	 }
    
	/**
	 * ��ת�������������ñ�ͷ�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת�����������������ñ�ͷҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭�����������ñ�ͷҳ��
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
	 * ��ת���鿴�����������ñ�ͷҳ��
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
<?php
/**
 * @author Administrator
 * @Date 2011��12��3�� 10:34:08
 * @version 1.0
 * @description:�豸�����嵥���Ʋ� 
 */
class controller_service_change_changeitem extends controller_base_action {

	function __construct() {
		$this->objName = "changeitem";
		$this->objPath = "service_change";
		parent::__construct ();
	 }
    
	/*
	 * ��ת���豸�����嵥�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת�������豸�����嵥ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭�豸�����嵥ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * ��ת���鿴�豸�����嵥ҳ��
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
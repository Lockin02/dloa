<?php
/**
 * @author Show
 * @Date 2011��12��27�� ���ڶ� 20:38:02
 * @version 1.0
 * @description:Ӧ��������Ʊ��ϸ���Ʋ� 
 */
class controller_finance_invother_invotherdetail extends controller_base_action {

	function __construct() {
		$this->objName = "invotherdetail";
		$this->objPath = "finance_invother";
		parent::__construct ();
	 }
    
	/*
	 * ��ת��Ӧ��������Ʊ��ϸ�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת������Ӧ��������Ʊ��ϸҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭Ӧ��������Ʊ��ϸҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * ��ת���鿴Ӧ��������Ʊ��ϸҳ��
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
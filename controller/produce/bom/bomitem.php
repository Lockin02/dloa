<?php
/**
 * @author Administrator
 * @Date 2011��12��30�� 11:43:26
 * @version 1.0
 * @description:BOM��¼����Ʋ� 
 */
class controller_produce_bom_bomitem extends controller_base_action {

	function __construct() {
		$this->objName = "bomitem";
		$this->objPath = "produce_bom";
		parent::__construct ();
	 }
    
	/*
	 * ��ת��BOM��¼���б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת������BOM��¼��ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭BOM��¼��ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * ��ת���鿴BOM��¼��ҳ��
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
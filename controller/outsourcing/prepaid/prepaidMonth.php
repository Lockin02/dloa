<?php
/**
 * @author Acan
 * @Date 2014��10��30�� 11:03:34
 * @version 1.0
 * @description:���Ԥ��_�·ݿ��Ʋ� 
 */
class controller_outsourcing_prepaid_prepaidMonth extends controller_base_action {

	function __construct() {
		$this->objName = "prepaidMonth";
		$this->objPath = "outsourcing_prepaid";
		parent::__construct ();
	 }
    
	/**
	 * ��ת�����Ԥ��_�·��б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת���������Ԥ��_�·�ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭���Ԥ��_�·�ҳ��
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
	 * ��ת���鿴���Ԥ��_�·�ҳ��
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
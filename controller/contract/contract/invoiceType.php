<?php
/**
 * @author Administrator
 * @Date 2014��2��11�� 10:18:16
 * @version 1.0
 * @description:��Ʊ���Ϳ��Ʋ� 
 */
class controller_contract_contract_invoiceType extends controller_base_action {

	function __construct() {
		$this->objName = "invoiceType";
		$this->objPath = "contract_contract";
		parent::__construct ();
	 }
    
	/**
	 * ��ת����Ʊ�����б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת��������Ʊ����ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭��Ʊ����ҳ��
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
	 * ��ת���鿴��Ʊ����ҳ��
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
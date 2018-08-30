<?php
/**
 * @author Administrator
 * @Date 2013��10��22�� ���ڶ� 14:22:33
 * @version 1.0
 * @description:��Ӧ��������Ϣ���Ʋ� 
 */
class controller_outsourcing_supplier_bankinfo extends controller_base_action {

	function __construct() {
		$this->objName = "bankinfo";
		$this->objPath = "outsourcing_supplier";
		parent::__construct ();
	 }
    
	/**
	 * ��ת����Ӧ��������Ϣ�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת��������Ӧ��������Ϣҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭��Ӧ��������Ϣҳ��
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
	 * ��ת���鿴��Ӧ��������Ϣҳ��
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
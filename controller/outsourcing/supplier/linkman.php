<?php
/**
 * @author Administrator
 * @Date 2013��10��22�� ���ڶ� 14:22:04
 * @version 1.0
 * @description:��Ӧ����ϵ�˿��Ʋ� 
 */
class controller_outsourcing_supplier_linkman extends controller_base_action {

	function __construct() {
		$this->objName = "linkman";
		$this->objPath = "outsourcing_supplier";
		parent::__construct ();
	 }
    
	/**
	 * ��ת����Ӧ����ϵ���б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת��������Ӧ����ϵ��ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭��Ӧ����ϵ��ҳ��
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
	 * ��ת���鿴��Ӧ����ϵ��ҳ��
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
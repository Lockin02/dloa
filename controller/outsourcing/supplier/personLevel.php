<?php
/**
 * @author Administrator
 * @Date 2013��11��5�� ���ڶ� 14:17:30
 * @version 1.0
 * @description:��Ա�������Ϳ��Ʋ� 
 */
class controller_outsourcing_supplier_personLevel extends controller_base_action {

	function __construct() {
		$this->objName = "personLevel";
		$this->objPath = "outsourcing_supplier";
		parent::__construct ();
	 }
    
	/**
	 * ��ת����Ա���������б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת��������Ա��������ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭��Ա��������ҳ��
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
	 * ��ת���鿴��Ա��������ҳ��
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
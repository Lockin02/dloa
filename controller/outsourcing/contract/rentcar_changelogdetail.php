<?php
/**
 * @author Show
 * @Date 2014��3��6�� ������ 10:12:51
 * @version 1.0
 * @description:�⳵��ͬ�����ϸ����Ʋ� 
 */
class controller_outsourcing_contract_rentcar_changelogdetail extends controller_base_action {

	function __construct() {
		$this->objName = "rentcar_changelogdetail";
		$this->objPath = "outsourcing_contract";
		parent::__construct ();
	 }
    
	/**
	 * ��ת���⳵��ͬ�����ϸ���б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת�������⳵��ͬ�����ϸ��ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭�⳵��ͬ�����ϸ��ҳ��
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
	 * ��ת���鿴�⳵��ͬ�����ϸ��ҳ��
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
<?php
/**
 * @author Show
 * @Date 2014��3��6�� ������ 10:13:50
 * @version 1.0
 * @description:�⳵��ͬǩ�ռ�¼����Ʋ� 
 */
class controller_outsourcing_contract_rentcar_signlog extends controller_base_action {

	function __construct() {
		$this->objName = "rentcar_signlog";
		$this->objPath = "outsourcing_contract";
		parent::__construct ();
	 }
    
	/**
	 * ��ת���⳵��ͬǩ�ռ�¼���б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת�������⳵��ͬǩ�ռ�¼��ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭�⳵��ͬǩ�ռ�¼��ҳ��
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
	 * ��ת���鿴�⳵��ͬǩ�ռ�¼��ҳ��
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
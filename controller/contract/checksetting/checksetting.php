<?php
/**
 * @author tse
 * @Date 2014��4��1�� 10:47:06
 * @version 1.0
 * @description:���չ������ÿ��Ʋ� 
 */
class controller_contract_checksetting_checksetting extends controller_base_action {

	function __construct() {
		$this->objName = "checksetting";
		$this->objPath = "contract_checksetting";
		parent::__construct ();
	 }
    
	/**
	 * ��ת�����չ��������б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת���������չ�������ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭���չ�������ҳ��
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
	 * ��ת���鿴���չ�������ҳ��
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
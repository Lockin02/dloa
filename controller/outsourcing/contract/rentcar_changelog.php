<?php
/**
 * @author Show
 * @Date 2014��3��6�� ������ 10:13:09
 * @version 1.0
 * @description:�⳵ͬ�����¼����Ʋ� 
 */
class controller_outsourcing_contract_rentcar_changelog extends controller_base_action {

	function __construct() {
		$this->objName = "rentcar_changelog";
		$this->objPath = "outsourcing_contract";
		parent::__construct ();
	 }
    
	/**
	 * ��ת���⳵ͬ�����¼���б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת�������⳵ͬ�����¼��ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭�⳵ͬ�����¼��ҳ��
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
	 * ��ת���鿴�⳵ͬ�����¼��ҳ��
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
<?php
/**
 * @author Michael
 * @Date 2014��3��6�� ������ 10:14:03
 * @version 1.0
 * @description:�⳵��ͬǩ����ϸ���Ʋ�
 */
class controller_outsourcing_contract_rentcar_signlogdetail extends controller_base_action {

	function __construct() {
		$this->objName = "rentcar_signlogdetail";
		$this->objPath = "outsourcing_contract";
		parent::__construct ();
	 }

	/**
	 * ��ת���⳵��ͬǩ����ϸ�б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת�������⳵��ͬǩ����ϸҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭�⳵��ͬǩ����ϸҳ��
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
	 * ��ת���鿴�⳵��ͬǩ����ϸҳ��
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
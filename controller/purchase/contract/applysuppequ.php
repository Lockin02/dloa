<?php
/**
 * @author Administrator
 * @Date 2012��12��14�� ������ 15:18:00
 * @version 1.0
 * @description:������Ӧ��_���۵������嵥���Ʋ�
 */
class controller_purchase_contract_applysuppequ extends controller_base_action {

	function __construct() {
		$this->objName = "applysuppequ";
		$this->objPath = "purchase_contract";
		parent::__construct ();
	 }

	/**
	 * ��ת��������Ӧ��_���۵������嵥�б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת������������Ӧ��_���۵������嵥ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add');
	}

   /**
	 * ��ת���༭������Ӧ��_���۵������嵥ҳ��
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
	 * ��ת���鿴������Ӧ��_���۵������嵥ҳ��
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
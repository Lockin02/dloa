<?php
/**
 * @author Michael
 * @Date 2014��3��24�� ����һ 14:50:04
 * @version 1.0
 * @description:�⳵��ͬ���޳�����Ϣ���Ʋ�
 */
class controller_outsourcing_contract_vehicle extends controller_base_action {

	function __construct() {
		$this->objName = "vehicle";
		$this->objPath = "outsourcing_contract";
		parent::__construct ();
	 }

	/**
	 * ��ת���⳵��ͬ���޳�����Ϣ�б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת�������⳵��ͬ���޳�����Ϣҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭�⳵��ͬ���޳�����Ϣҳ��
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
	 * ��ת���鿴�⳵��ͬ���޳�����Ϣҳ��
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
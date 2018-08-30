<?php
/**
 * @author Michael
 * @Date 2014��10��8�� 19:50:39
 * @version 1.0
 * @description:�⳵�ǼǺ�ͬ���ӷѿ��Ʋ�
 */
class controller_outsourcing_vehicle_registerfee extends controller_base_action {

	function __construct() {
		$this->objName = "registerfee";
		$this->objPath = "outsourcing_vehicle";
		parent::__construct ();
	 }

	/**
	 * ��ת���⳵�ǼǺ�ͬ���ӷ��б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת�������⳵�ǼǺ�ͬ���ӷ�ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭�⳵�ǼǺ�ͬ���ӷ�ҳ��
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
	 * ��ת���鿴�⳵�ǼǺ�ͬ���ӷ�ҳ��
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
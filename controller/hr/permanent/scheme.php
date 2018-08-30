<?php
/**
 * @author jianjungki
 * @Date 2012��8��3�� 11:01:18
 * @version 1.0
 * @description:Ա�����˷������Ʋ�
 */
class controller_hr_permanent_scheme extends controller_base_action {

	function __construct() {
		$this->objName = "scheme";
		$this->objPath = "hr_permanent";
		parent::__construct ();
	 }

	/*
	 * ��ת��Ա�����˷����б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת������Ա�����˷���ҳ��
	 */
	function c_toAdd() {
		$this->view ('add' ,true);
	}

   /**
	 * ��ת���༭Ա�����˷���ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('schemeType' => 'HRKHJB' ), $obj ['schemeTypeCode'] ,true);
		$this->view ('edit' ,true);
	}

   /**
	 * ��ת���鿴Ա�����˷���ҳ��
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
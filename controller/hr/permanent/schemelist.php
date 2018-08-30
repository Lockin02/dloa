<?php
/**
 * @author jianjungki
 * @Date 2012��8��6�� 15:23:48
 * @version 1.0
 * @description:Ա��������Ŀϸ����Ʋ�
 */
class controller_hr_permanent_schemelist extends controller_base_action {

	function __construct() {
		$this->objName = "schemelist";
		$this->objPath = "hr_permanent";
		parent::__construct ();
	 }

	/*
	 * ��ת��Ա��������Ŀϸ���б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת������Ա��������Ŀϸ��ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭Ա��������Ŀϸ��ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴Ա��������Ŀϸ��ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->asc = false;
		$rows = $service->list_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
 }
?>
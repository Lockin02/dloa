<?php
/**
 * @author Administrator
 * @Date 2012��8��6�� 21:39:29
 * @version 1.0
 * @description:Ա��ת�����˹�����ؿ��Ʋ�
 */
class controller_hr_permanent_linkcontent extends controller_base_action {

	function __construct() {
		$this->objName = "linkcontent";
		$this->objPath = "hr_permanent";
		parent::__construct ();
	 }

	/*
	 * ��ת��Ա��ת�����˹�������б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת������Ա��ת�����˹������ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭Ա��ת�����˹������ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴Ա��ת�����˹������ҳ��
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
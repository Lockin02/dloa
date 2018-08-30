<?php
/**
 * @author Administrator
 * @Date 2012-08-09 15:38:12
 * @version 1.0
 * @description:��ְ�����嵥��ϸ���Ʋ�
 */
class controller_hr_leave_handoverlist extends controller_base_action {

	function __construct() {
		$this->objName = "handoverlist";
		$this->objPath = "hr_leave";
		parent::__construct ();
	 }

	/**
	 * ��ת����ְ�����嵥��ϸ�б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת��������ְ�����嵥��ϸҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭��ְ�����嵥��ϸҳ��
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
	 * ��ת���鿴��ְ�����嵥��ϸҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_addItemJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->asc=false;
		$rows = $service->list_d ("select_default");

		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_addItemJsonList() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->asc=false;
		$rows = $service->list_d ("select_default");

		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/*
	 * ��ְ�����嵥����
	 */
	function c_restart() {
		$this->permCheck ();
		if ($this->service->restart_d ( $_POST ['handover'] )) {
			 msg ( '�����ɹ���' );
		}
	}

	/*
	 * ��ְ�����嵥�޸�
	 */
	function c_alterHand() {
		$this->permCheck ();
		//var_dump($_POST ['handover']);exit();
		if ($this->service->alterHand_d ( $_POST ['handover'] )) {
			 msg ( '�޸ĳɹ���' );
		}
	}
}
?>
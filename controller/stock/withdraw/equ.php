<?php
/**
 * @author Administrator
 * @Date 2012��11��8�� 11:04:46
 * @version 1.0
 * @description:�ջ�֪ͨ�����嵥���Ʋ�
 */
class controller_stock_withdraw_equ extends controller_base_action {

	function __construct() {
		$this->objName = "equ";
		$this->objPath = "stock_withdraw";
		parent::__construct ();
	 }

	/*
	 * ��ת���ջ�֪ͨ�����嵥�б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת�������ջ�֪ͨ�����嵥ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭�ջ�֪ͨ�����嵥ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴�ջ�֪ͨ�����嵥ҳ��
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
		$rows = $service->list_d ();
//		echo "<pre>";
//		print_R($rows);
//		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJsonInnotice() {
		$service = $this->service;
		$_REQUEST['numSql'] = 'sql:and (c.executedNum < (c.qPassNum + c.qBackNum))';
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
//		echo "<pre>";
//		print_R($rows);
//		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}
?>
<?php
/**
 * @author Liub
 * @Date 2012-04-07 14:03:40
 * @version 1.0
 * @description:�����˻������嵥���Ʋ�
 */
class controller_projectmanagent_exchange_exchangebackequ extends controller_base_action {

	function __construct() {
		$this->objName = "exchangebackequ";
		$this->objPath = "projectmanagent_exchange";
		parent::__construct ();
	 }

	/*
	 * ��ת�������˻������嵥�б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת�����������˻������嵥ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭�����˻������嵥ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴�����˻������嵥ҳ��
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
		$rows = $service->list_d ("select_edit");
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
 }
?>
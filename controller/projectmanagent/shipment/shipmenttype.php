<?php
/**
 * @author Administrator
 * @Date 2012��5��23�� 9:50:17
 * @version 1.0
 * @description:���������Զ������Ϳ��Ʋ�
 */
class controller_projectmanagent_shipment_shipmenttype extends controller_base_action {

	function __construct() {
		$this->objName = "shipmenttype";
		$this->objPath = "projectmanagent_shipment";
		parent::__construct ();
	 }

	/*
	 * ��ת�����������Զ��������б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת���������������Զ�������ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭���������Զ�������ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴���������Զ�������ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }

	/**
	 * grid�б���������
	 */
	function c_getSelection(){
		$rows = $this->service->list_d();
		$datas = array();
		foreach( $rows as $key=>$val ){
			$datas[$key]['text']=$val['type'];
			$datas[$key]['value']=$val['id'];
		}
		echo util_jsonUtil::encode ( $datas );
	}

 }
?>
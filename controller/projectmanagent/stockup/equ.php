<?php
/**
 * @author Administrator
 * @Date 2012-09-25 09:54:23
 * @version 1.0
 * @description:���۱��������嵥���Ʋ�
 */
class controller_projectmanagent_stockup_equ extends controller_base_action {

	function __construct() {
		$this->objName = "equ";
		$this->objPath = "projectmanagent_stockup";
		parent::__construct ();
	 }

	/*
	 * ��ת�����۱��������嵥�б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת���������۱��������嵥ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭���۱��������嵥ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴���۱��������嵥ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }


	/**
	 * ��ȡ������Ϣ
	 */
	function c_addlistJson() {
		$service = $this->service;
//		$service->getParam ( $_REQUEST );
		$dao = new model_stock_productinfo_productinfo();
		$rows = $dao->get_d($_POST['equId']);
	  $arr[0]['productId'] = $rows['id'];
	  $arr[0]['productCode'] = $rows['productCode'];
	  $arr[0]['productName'] = $rows['productName'];
	  $arr[0]['productModel'] = $rows['pattern'];
	  $arr[0]['unitName'] = $rows['unitName'];

		//���ݼ��밲ȫ��
		$arr = $this->sconfig->md5Rows ( $arr );
		echo util_jsonUtil::encode ( $arr );
	}

 }
?>
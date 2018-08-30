<?php

/*
 * Created on 2010-7-20
 *  ��Ʒ������Ϣ
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class controller_stock_productinfo_configuration extends controller_base_action {

	function __construct() {
		$this->objName = "configuration";
		$this->objPath = "stock_productinfo";
		parent::__construct ();
	}

	/**
	 *
	 * ͨ������id�鿴�����Ϣ
	 */
	function c_viewConfig() {
		$productId = isset ( $_GET ['productId'] ) ? $_GET ['productId'] : null;
		$this->service->searchArr = array ("hardWareId" => $productId, "configType" => "proaccess" );
		$rows = $this->service->list_d ();

		$this->assign ( "configItem", $this->service->showConfigItem ( $rows ) );
		$this->view ( "item" );
	}

	/**
	 * ��ȡ�������� ��Ϣpagejson
	 * by LiuB
	 */
	function c_conPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$conId = $_POST ['productId'];
		$service->searchArr ['hardWareId'] = $conId;
		$rows = $service->pageBySqlId ( 'select_default' );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}


	/**
	 * ��������id��ȡ��� ����Ӧ������Ϣ
	 */
	function c_getAccessForPro(){
		$arr=$this->service->getAccessForPro($_POST['productId']);
//		echo "<pre>";
//		print_r($arr);
		echo util_jsonUtil::encode ($arr);
	}


	/**
	 * ��ȡ����Json
	 */
	function c_itemJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->searchArr['configType']="proconfig";
		$rows = $service->list_d ();
		$arr = array ();
		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $arr );
	}
}
?>

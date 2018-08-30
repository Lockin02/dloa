<?php

/*
 * Created on 2010-7-20
 *  产品配置信息
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
	 * 通过物料id查看配件信息
	 */
	function c_viewConfig() {
		$productId = isset ( $_GET ['productId'] ) ? $_GET ['productId'] : null;
		$this->service->searchArr = array ("hardWareId" => $productId, "configType" => "proaccess" );
		$rows = $this->service->list_d ();

		$this->assign ( "configItem", $this->service->showConfigItem ( $rows ) );
		$this->view ( "item" );
	}

	/**
	 * 获取物料配置 信息pagejson
	 * by LiuB
	 */
	function c_conPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		$conId = $_POST ['productId'];
		$service->searchArr ['hardWareId'] = $conId;
		$rows = $service->pageBySqlId ( 'select_default' );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}


	/**
	 * 根据物料id获取配件 及对应物料信息
	 */
	function c_getAccessForPro(){
		$arr=$this->service->getAccessForPro($_POST['productId']);
//		echo "<pre>";
//		print_r($arr);
		echo util_jsonUtil::encode ($arr);
	}


	/**
	 * 获取配置Json
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

<?php
/**
*资产领用明细控制层
*@linzx
 */
class controller_asset_daily_chargeitem extends controller_base_action {

	function __construct() {
		$this->objName = "chargeitem";
		$this->objPath = "asset_daily";
		parent::__construct ();
	 }

	/*
	 * 跳转到资产领用清单
	 */
    function c_page() {

      $this->view('list');
    }
/**
//	 * 获取所有数据返回json
//	 */
//	function c_listpageJson() {
//		$service = $this->service;
//		$service->getParam ( $_REQUEST );
//		$rows = $service->list_d ();
//		//数据加入安全码
//		$rows = $this->sconfig->md5Rows ( $rows );
//		$idArr = array();
//		foreach($rows as $key=>$val){
//			$idArr[$key] = $val['assetId'];
//		}
//		$cardDao = new model_asset_assetcard_assetcard();
//		$cardInfos = $cardDao->getCardsByIdArr($idArr);
//		echo util_jsonUtil::encode ( $cardInfos );
//	}


//	/**
//	 * 获取所有数据返回json
//	 */
//	function c_getReturnRowsJson() {
//		$service = $this->service;
//		$service->getParam ( $_REQUEST );
//		$mainDao = new model_asset_daily_return();
//		$returnAssetIds = $mainDao->getAssetIdByDocId($_REQUEST['allocateID'],'oa_asset_charge');
//		if($returnAssetIds!=''){
//			$service->searchArr['beyongAssetId']=$returnAssetIds;
//		}
//		$rows = $service->list_d ();
//		//数据加入安全码
//		$rows = $this->sconfig->md5Rows ( $rows );
////		echo "<pre>";
////		print_R($rows);
//		$idArr = array();
//		foreach($rows as $key=>$val){
//			$idArr[$key] = $val['assetId'];
//		}
//		$condition = array(
//			'useStatusCode'=>'SYZT-SYZ'
//		);
//		$cardDao = new model_asset_assetcard_assetcard();
//		$cardInfos = $cardDao->getCardsByIdArr($idArr,$condition);
//		echo util_jsonUtil::encode ( $cardInfos );
//	}


	/**
	 * 获取所有数据返回json
	 */
	function c_getReturnRowsJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$mainDao = new model_asset_daily_return();
		$returnAssetIds = $mainDao->getAssetIdByDocId($_REQUEST['allocateID'],'oa_asset_charge');
		if($returnAssetIds!=''){
			$service->searchArr['beyongAssetId']=$returnAssetIds;
		}
		$rows = $service->list_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
//		echo "<pre>";
//		print_R($rows);
		$idArr = array();
		foreach($rows as $key=>$val){
			$idArr[$key] = $val['assetId'];
		}
		$condition = array(
			'useStatusCode'=>'SYZT-SYZ'
		);
		$cardDao = new model_asset_assetcard_assetcard();
		$cardInfos = $cardDao->getCardsByIdArr($idArr,$condition);
		echo util_jsonUtil::encode ( $cardInfos );
	}


 }
?>
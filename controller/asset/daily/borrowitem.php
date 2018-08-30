<?php
/**
 * @author chenzb
 * @Date 2011年11月21日
 * @version 1.0
 * @description:资产借用清单控制层
 */
class controller_asset_daily_borrowitem extends controller_base_action {

	function __construct() {
		$this->objName = "borrowitem";
		$this->objPath = "asset_daily";
		parent::__construct ();
	 }

	/*
	 * 跳转到资产借用清单
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }



//	/**
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


	/**
	 * 获取所有数据返回json
	 */
	function c_getReturnRowsJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$mainDao = new model_asset_daily_return();
		$returnAssetIds = $mainDao->getAssetIdByDocId($_REQUEST['borrowId'],'oa_asset_borrow');
		if($returnAssetIds!=''){
			$service->searchArr['beyongAssetId']=$returnAssetIds;
		}
		$rows = $service->list_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
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
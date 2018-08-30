<?php
/**
 * @author chenzb
 * @Date 2011��11��21��
 * @version 1.0
 * @description:�ʲ������嵥���Ʋ�
 */
class controller_asset_daily_borrowitem extends controller_base_action {

	function __construct() {
		$this->objName = "borrowitem";
		$this->objPath = "asset_daily";
		parent::__construct ();
	 }

	/*
	 * ��ת���ʲ������嵥
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }



//	/**
//	 * ��ȡ�������ݷ���json
//	 */
//	function c_listpageJson() {
//		$service = $this->service;
//		$service->getParam ( $_REQUEST );
//		$rows = $service->list_d ();
//		//���ݼ��밲ȫ��
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
	 * ��ȡ�������ݷ���json
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
		//���ݼ��밲ȫ��
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
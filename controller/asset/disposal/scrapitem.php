<?php
class controller_asset_disposal_scrapitem extends controller_base_action {
		function __construct() {
		$this->objName = "scrapitem";
		$this->objPath = "asset_disposal";
		parent::__construct ();
	}

		/*
	 * ��ת���ʲ�����������ϸ��������ô�ôӱ�����ݣ�
	 *
	 */
    function c_page() {
      $this->view('list');
    }

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_getScrapRowsJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$idArr = array();
		foreach($rows as $key=>$val){
			$idArr[$key] = $val['assetId'];
		}
//		$condition = array(
//			'useStatusCode'=>'SYZT-SYZ'
//		);
		$cardDao = new model_asset_assetcard_assetcard();
		$cardInfos = $cardDao->getCardsByIdArr($idArr,true);
		echo util_jsonUtil::encode ( $cardInfos );
	}
}

<?php

/**
 * �ʲ���ʧ��ϸmodel����
 *@linzx
 */
class model_asset_daily_loseitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_loseitem";
		$this->sql_map = "asset/daily/loseitemSql.php";
		parent :: __construct();

	}

	/**
	 * �޸���ʧ�ʲ��嵥�Ƿ񱨷�״̬�������ѱ���
	 * @linzx
	 */
	function setScrapStatus($mainId,$assetId){
		$condition = array(
			'loseId'=>$mainId,
			'assetId'=>$assetId
		);
		return $this->updateField($condition,'isScrap','1');
	}
	/**
	 * �޸���ʧ�ʲ��嵥�Ƿ񱨷�״̬������δ����
	 * @linzx
	 */
		function setNoScrapStatus($mainId,$assetId){
		$condition = array(
			'loseId'=>$mainId,
			'assetId'=>$assetId
		);
		return $this->updateField($condition,'isScrap','0');
	}
}
?>
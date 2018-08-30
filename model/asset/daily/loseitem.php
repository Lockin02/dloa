<?php

/**
 * 资产遗失明细model层类
 *@linzx
 */
class model_asset_daily_loseitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_loseitem";
		$this->sql_map = "asset/daily/loseitemSql.php";
		parent :: __construct();

	}

	/**
	 * 修改遗失资产清单是否报废状态将其变成已报废
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
	 * 修改遗失资产清单是否报废状态将其变成未报废
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
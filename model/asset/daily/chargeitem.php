<?php

/**
 * 资产领用明细model层类
 *@linzx
 */
class model_asset_daily_chargeitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_chargeitem";
		$this->sql_map = "asset/daily/chargeitemSql.php";
		parent :: __construct();

	}
	/**
	 * 根据主表ID和资产ID修改资产清单状态
	 */
	function setEquStatus($mainId,$assetId,$status){
		$condition = array(
			'allocateID'=>$mainId,
			'assetId'=>$assetId
		);
		$rows = array(
			'isReturn'=>$status,
			'returnDate'=>day_date
		);
		return $this->update($condition,$rows);
	}

}
?>
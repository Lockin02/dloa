<?php

/**
 * �ʲ�������ϸmodel����
 *@linzx
 */
class model_asset_daily_chargeitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_chargeitem";
		$this->sql_map = "asset/daily/chargeitemSql.php";
		parent :: __construct();

	}
	/**
	 * ��������ID���ʲ�ID�޸��ʲ��嵥״̬
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
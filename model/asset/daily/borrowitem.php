<?php
/**

 * @description:资产借用清单 Model层
 */
 class model_asset_daily_borrowitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_borrowitem";
		$this->sql_map = "asset/daily/borrowitemSql.php";
		parent::__construct ();
	}
	/**
	 * 根据主表ID和资产ID修改资产清单状态 -- 是否归还字段
	 */
	function setEquStatus($mainId,$assetId,$status){
		$condition = array(
			'borrowId'=>$mainId,
			'assetId'=>$assetId
		);
		$rows = array(
			'isReturn'=>$status,
			'returnDate'=>day_date
		);
		return $this->update($condition,$rows);
	}
	/**
	 * 根据资产id修改字段值
	 */
	 function setFields($condition,$field,$value){
	 	$this->updateField($condition,$field,$value);
	 }

	 /**
	  * 根据资产id，查找是否有保存归还的资产
	  */
	  function getSaveReturn_d($assetId){
	  	$sql = "select count(0) as countNum from ".$this->tbl_name." where isReturn=0 and saveReturn=1 and assetId=".$assetId;
	  	$row = $this->_db->getArray($sql);
	  	return $row['countNum'];
	  }

 }
?>
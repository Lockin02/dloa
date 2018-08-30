<?php
/**

 * @description:�ʲ������嵥 Model��
 */
 class model_asset_daily_borrowitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_borrowitem";
		$this->sql_map = "asset/daily/borrowitemSql.php";
		parent::__construct ();
	}
	/**
	 * ��������ID���ʲ�ID�޸��ʲ��嵥״̬ -- �Ƿ�黹�ֶ�
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
	 * �����ʲ�id�޸��ֶ�ֵ
	 */
	 function setFields($condition,$field,$value){
	 	$this->updateField($condition,$field,$value);
	 }

	 /**
	  * �����ʲ�id�������Ƿ��б���黹���ʲ�
	  */
	  function getSaveReturn_d($assetId){
	  	$sql = "select count(0) as countNum from ".$this->tbl_name." where isReturn=0 and saveReturn=1 and assetId=".$assetId;
	  	$row = $this->_db->getArray($sql);
	  	return $row['countNum'];
	  }

 }
?>
<?php
/**
 * @author Administrator
 * @Date 2011年11月23日 9:51:30
 * @version 1.0
 * @description:变动记录 Model层
 */
 class model_asset_change_assetchange extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_alter";
		$this->sql_map = "asset/change/assetchangeSql.php";
		parent::__construct ();
	}

	function addRecord_d($relInfo,$type=false){
		$id = $this->add_d ( $relInfo, true );
	}

	function getChangeInfoByAssetId($assetId){
		$condition = array(
			'oldAssetId'=>$assetId
		);
		$row = $this->find($condition);
		return $row;
	}

	function getChangeRecord($assetId){
		$this->pageSize=1;
		$this->asc=true;
		$this->searchArr['assetId']=$assetId;
		$rows = $this->page_d();
		return $rows[0];
	}


	function getSecondChangeRecord($assetId){
		$this->pageSize=2;
		$this->asc=true;
		$this->searchArr['assetId']=$assetId;
		$rows = $this->page_d();
		return $rows[1];
	}

 }
?>
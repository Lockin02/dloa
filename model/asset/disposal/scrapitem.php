<?php

/**
 * 资产报废明细model层类
 *@linzx
 */
class model_asset_disposal_scrapitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_scrapitem";
		$this->sql_map = "asset/disposal/scrapitemSql.php";
		parent :: __construct();


	}


	/**
	 * 根据主表ID和资产ID修改资产清单状态
	 * @linzx
	 */
	function setSellStatus($mainId){
		$condition = array(
			'id'=>$mainId,
		);
		return $this->updateField($condition,'sellStatus','已出售');
	}



}
?>
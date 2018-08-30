<?php


/**
 * 清理记录model层类
 *@linzx
 */
class model_asset_assetcard_clean extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_clean";
		$this->sql_map = "asset/assetcard/cleanSql.php";
		parent :: __construct();

	}

	/**
	 *从数据库里获取数据在下拉框里作选项
	 *@linzx
	 */
	function showSelect_d($name) {
		if (is_array($name)) {
			foreach ($name as $k => $v) {
				$str .= '<option value="' . $v['name'] . '">';
				$str .= $v['name'];
				$str .= '</option>';
			}
			return $str;
		}
	}

	/**
	* 根据Id 拿到该数据的清理表资产id
	* @linzx
	*/
	function getAssetIdById_d($id) {
		$dirObj = $this->get_d($id);
		$assetId = $dirObj['assetId'];
		$scrapDao = new model_asset_assetcard_assetcard();
		return $scrapDao->setCleanStatus($assetId);
	}

}
?>
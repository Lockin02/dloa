<?php


/**
 * �����¼model����
 *@linzx
 */
class model_asset_assetcard_clean extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_clean";
		$this->sql_map = "asset/assetcard/cleanSql.php";
		parent :: __construct();

	}

	/**
	 *�����ݿ����ȡ����������������ѡ��
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
	* ����Id �õ������ݵ�������ʲ�id
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
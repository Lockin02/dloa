<?php

/**
 * �ʲ�������ϸmodel����
 *@linzx
 */
class model_asset_disposal_scrapitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_scrapitem";
		$this->sql_map = "asset/disposal/scrapitemSql.php";
		parent :: __construct();


	}


	/**
	 * ��������ID���ʲ�ID�޸��ʲ��嵥״̬
	 * @linzx
	 */
	function setSellStatus($mainId){
		$condition = array(
			'id'=>$mainId,
		);
		return $this->updateField($condition,'sellStatus','�ѳ���');
	}



}
?>
<?php
/**
 * @author liangjj
 * @Date 2013��9��22�� 14:30:28
 * @version 1.0
 * @description:�����ͬ��Ա���� Model��
 */
class model_contract_personrental_personrental extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_outsourcing_personrental";
		$this->sql_map = "contract/personrental/personrentalSql.php";
		parent :: __construct();
	}

	//ɾ��������Ϣ
	function delItemInfo_d($id) {
		$sql = "delete from " . $this->tbl_name . " where mainId = '$id' ";
		return $this->query($sql);
	}
}
?>
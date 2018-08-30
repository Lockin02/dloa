<?php
/**
 * @author liangjj
 * @Date 2013年9月22日 14:30:28
 * @version 1.0
 * @description:外包合同人员租赁 Model层
 */
class model_contract_personrental_personrental extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_outsourcing_personrental";
		$this->sql_map = "contract/personrental/personrentalSql.php";
		parent :: __construct();
	}

	//删除租赁信息
	function delItemInfo_d($id) {
		$sql = "delete from " . $this->tbl_name . " where mainId = '$id' ";
		return $this->query($sql);
	}
}
?>
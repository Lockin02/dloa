<?php
/**
 * @author Show
 * @Date 2011年12月27日 星期二 20:38:02
 * @version 1.0
 * @description:应付其他发票明细 Model层
 */
class model_finance_invother_invotherdetail extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_invother_detail";
		$this->sql_map = "finance/invother/invotherdetailSql.php";
		parent :: __construct();
	}
}
?>
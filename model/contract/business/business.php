<?php
class model_contract_business_business extends model_base {

	function __construct() {
		$this->tbl_name = "";
		$this->sql_map = "contract/business/businessSql.php";
		parent :: __construct();
	}


}
?>

<?php
class model_system_portal_usercustomize extends model_base {

	function __construct() {
		$this->tbl_name = "oa_portal_user_customize";
		$this->sql_map = "system/portal/usercustomizeSql.php";
		parent :: __construct();
	}
}
?>

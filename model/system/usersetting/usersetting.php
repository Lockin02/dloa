<?php 
class model_system_usersetting_usersetting extends model_base {
	function __construct() {
		$this->tbl_name = "oa_system_user_setting";
		$this->sql_map = "system/usersetting/usersettingSql.php";
		parent::__construct ();
	}
	
}
?>
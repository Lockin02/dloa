<?php

/**
 * 
 * ¹ú¼Òmodel
 * @author chris
 *
 */
class model_system_procity_country extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_system_country_info";
		$this->sql_map = "system/procity/countrySql.php";
		parent::__construct ();
	}

}
?>

<?php

/**
 *ҵ���������켣model��
 */
class model_system_log_tracklog extends model_base {

	function __construct($opObjType,$opMethod) {
		$this->tbl_name = "oa_system_track_log";
		$this->sql_map = "system/log/tracklogSql.php";
		parent :: __construct();
	}


}
?>
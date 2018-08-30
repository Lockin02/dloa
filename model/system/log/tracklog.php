<?php

/**
 *业务对象操作轨迹model层
 */
class model_system_log_tracklog extends model_base {

	function __construct($opObjType,$opMethod) {
		$this->tbl_name = "oa_system_track_log";
		$this->sql_map = "system/log/tracklogSql.php";
		parent :: __construct();
	}


}
?>
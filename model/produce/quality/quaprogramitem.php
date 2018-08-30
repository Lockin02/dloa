<?php

/**
 * @author Show
 * @Date 2013年5月20日 星期一 13:48:57
 * @version 1.0
 * @description:质检方案分录 Model层
 */
class model_produce_quality_quaprogramitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_quality_programitem";
		$this->sql_map = "produce/quality/quaprogramitemSql.php";
		parent :: __construct();
	}
}
?>
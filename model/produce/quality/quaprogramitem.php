<?php

/**
 * @author Show
 * @Date 2013��5��20�� ����һ 13:48:57
 * @version 1.0
 * @description:�ʼ췽����¼ Model��
 */
class model_produce_quality_quaprogramitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_quality_programitem";
		$this->sql_map = "produce/quality/quaprogramitemSql.php";
		parent :: __construct();
	}
}
?>
<?php

/**
 * @author Administrator
 * @Date 2012-09-08 14:24:37
 * @version 1.0
 * @description:商机团队成员权限表 Model层
 */
class model_projectmanagent_chance_authorize extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_chance_authorize";
		$this->sql_map = "projectmanagent/chance/authorizeSql.php";
		parent :: __construct();
	}
}
?>
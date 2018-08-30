<?php
/**
 * @author Administrator
 * @Date 2012-08-03 14:08:32
 * @version 1.0
 * @description:商机推进信息 Model层
 */
 class model_projectmanagent_chance_boost  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_chance_boost";
		$this->sql_map = "projectmanagent/chance/boostSql.php";
		parent::__construct ();
	}     }
?>
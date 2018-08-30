<?php
/**
 * @author Administrator
 * @Date 2011年3月5日 10:20:18
 * @version 1.0
 * @description:线索跟踪人 Model层 
 */
 class model_projectmanagent_clues_trackman  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_clues_trackman";
		$this->sql_map = "projectmanagent/clues/trackmanSql.php";
		parent::__construct ();
	}
 }
?>
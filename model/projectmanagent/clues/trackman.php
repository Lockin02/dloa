<?php
/**
 * @author Administrator
 * @Date 2011��3��5�� 10:20:18
 * @version 1.0
 * @description:���������� Model�� 
 */
 class model_projectmanagent_clues_trackman  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_clues_trackman";
		$this->sql_map = "projectmanagent/clues/trackmanSql.php";
		parent::__construct ();
	}
 }
?>
<?php
/**
 * @author Administrator
 * @Date 2012-08-02 15:58:33
 * @version 1.0
 * @description:¾ºÕù¶ÔÊÖ Model²ã
 */
 class model_projectmanagent_chance_competitor  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_chance_competitor";
		$this->sql_map = "projectmanagent/chance/competitorSql.php";
		parent::__construct ();
	}     }
?>
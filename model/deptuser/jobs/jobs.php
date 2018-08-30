<?php

/**
 * ½ÇÉ«model²ãÀà
 */
class model_deptuser_jobs_jobs extends model_base {

	function __construct() {
		$this->tbl_name = "user_jobs";
		$this->sql_map = "deptuser/jobs/jobsSql.php";
		parent::__construct ();
	}



}
?>
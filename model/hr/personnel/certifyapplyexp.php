<?php
/**
 * @author Show
 * @Date 2012年8月22日 星期三 17:25:00
 * @version 1.0
 * @description:任职资格-本专业领域经历 Model层 
 */
 class model_hr_personnel_certifyapplyexp  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_personnel_certifyapplyexp";
		$this->sql_map = "hr/personnel/certifyapplyexpSql.php";
		parent::__construct ();
	}     
 }
?>
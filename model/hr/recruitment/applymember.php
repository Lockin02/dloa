<?php
/**
 * @author Administrator
 * @Date 2012年7月14日 星期六 14:10:24
 * @version 1.0
 * @description:增员申请协助人 Model层 
 */
 class model_hr_recruitment_applymember  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_apply_menber";
		$this->sql_map = "hr/recruitment/applymemberSql.php";
		parent::__construct ();
	}     
 }
?>
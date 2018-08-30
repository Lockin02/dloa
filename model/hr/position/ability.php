<?php
/**
 * @author Administrator
 * @Date 2012年7月9日 星期一 14:16:48
 * @version 1.0
 * @description:职位能力要求 Model层 
 */
 class model_hr_position_ability  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_position_ability";
		$this->sql_map = "hr/position/abilitySql.php";
		parent::__construct ();
	}     
 }
?>
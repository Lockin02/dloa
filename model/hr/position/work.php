<?php
/**
 * @author Administrator
 * @Date 2012年7月9日 星期一 14:16:18
 * @version 1.0
 * @description:职位工作职责 Model层 
 */
 class model_hr_position_work  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_position_work";
		$this->sql_map = "hr/position/workSql.php";
		parent::__construct ();
	}     
 }
?>
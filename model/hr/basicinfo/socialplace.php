<?php
/**
 * @author Administrator
 * @Date 2012年8月11日 星期六 10:27:17
 * @version 1.0
 * @description:社保购买地 Model层 
 */
 class model_hr_basicinfo_socialplace  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_socialsecurity_place";
		$this->sql_map = "hr/basicinfo/socialplaceSql.php";
		parent::__construct ();
	}     
 }
?>
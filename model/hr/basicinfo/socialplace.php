<?php
/**
 * @author Administrator
 * @Date 2012��8��11�� ������ 10:27:17
 * @version 1.0
 * @description:�籣����� Model�� 
 */
 class model_hr_basicinfo_socialplace  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_socialsecurity_place";
		$this->sql_map = "hr/basicinfo/socialplaceSql.php";
		parent::__construct ();
	}     
 }
?>
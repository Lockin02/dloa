<?php
/**
 * @author Administrator
 * @Date 2012��7��9�� ����һ 14:16:18
 * @version 1.0
 * @description:ְλ����ְ�� Model�� 
 */
 class model_hr_position_work  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_position_work";
		$this->sql_map = "hr/position/workSql.php";
		parent::__construct ();
	}     
 }
?>
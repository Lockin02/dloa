<?php
/**
 * @author Administrator
 * @Date 2012��7��9�� ����һ 14:16:48
 * @version 1.0
 * @description:ְλ����Ҫ�� Model�� 
 */
 class model_hr_position_ability  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_position_ability";
		$this->sql_map = "hr/position/abilitySql.php";
		parent::__construct ();
	}     
 }
?>
<?php
/**
 * @author jianjungki
 * @Date 2012��8��6�� 15:23:48
 * @version 1.0
 * @description:Ա��������Ŀϸ�� Model�� 
 */
 class model_hr_permanent_schemelist  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_permanent_schemelist";
		$this->sql_map = "hr/permanent/schemelistSql.php";
		parent::__construct ();
	}     
 }
?>
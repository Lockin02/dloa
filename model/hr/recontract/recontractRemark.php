<?php
/**
 * @author Administrator
 * @Date 2012-10-24 15:52:02
 * @version 1.0
 * @description:±¸×¢ Model²ã
 */
 class model__hr_recontract_recontractRemark  extends model_base {

	function __construct() {
		$this->tbl_name = "hr_recontract_remark";
		$this->sql_map = "hr/recontract/recontractRemarkSql.php";
		parent::__construct ();
	}     
}
?>
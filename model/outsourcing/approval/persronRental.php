<?php
/**
 * @author Administrator
 * @Date 2013年11月2日 星期六 14:03:48
 * @version 1.0
 * @description:外包立项人员租赁 Model层 
 */
 class model_outsourcing_approval_persronRental  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcing_approval_personrental";
		$this->sql_map = "outsourcing/approval/persronRentalSql.php";
		parent::__construct ();
	}     
 }
?>
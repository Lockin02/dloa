<?php
/**
 * @author Administrator
 * @Date 2013��11��2�� ������ 14:03:48
 * @version 1.0
 * @description:���������Ա���� Model�� 
 */
 class model_outsourcing_approval_persronRental  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcing_approval_personrental";
		$this->sql_map = "outsourcing/approval/persronRentalSql.php";
		parent::__construct ();
	}     
 }
?>
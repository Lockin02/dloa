<?php
/**
 * @author Show
 * @Date 2014��1��20�� ����һ 15:32:49
 * @version 1.0
 * @description:�⳵������������ӱ� Model�� 
 */
 class model_outsourcing_vehicle_rentalcarequ  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcing_rentalcarequ";
		$this->sql_map = "outsourcing/vehicle/rentalcarequSql.php";
		parent::__construct ();
	}     
 }
?>
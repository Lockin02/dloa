<?php
/**
 * @author Show
 * @Date 2014年1月20日 星期一 15:32:49
 * @version 1.0
 * @description:租车的申请与受理从表 Model层 
 */
 class model_outsourcing_vehicle_rentalcarequ  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcing_rentalcarequ";
		$this->sql_map = "outsourcing/vehicle/rentalcarequSql.php";
		parent::__construct ();
	}     
 }
?>
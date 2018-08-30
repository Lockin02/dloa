<?php
/**
 * @author yxin1
 * @Date 2014年10月8日 19:50:39
 * @version 1.0
 * @description:租车登记合同附加费 Model层 
 */
 class model_outsourcing_vehicle_registerfee  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcing_registerfee";
		$this->sql_map = "outsourcing/vehicle/registerfeeSql.php";
		parent::__construct ();
	}     
 }
?>
<?php
/**
 * @author yxin1
 * @Date 2014��10��8�� 19:50:39
 * @version 1.0
 * @description:�⳵�ǼǺ�ͬ���ӷ� Model�� 
 */
 class model_outsourcing_vehicle_registerfee  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcing_registerfee";
		$this->sql_map = "outsourcing/vehicle/registerfeeSql.php";
		parent::__construct ();
	}     
 }
?>
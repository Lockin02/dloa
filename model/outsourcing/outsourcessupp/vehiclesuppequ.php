<?php
/**
 * @author Show
 * @Date 2014��1��7�� ���ڶ� 9:21:37
 * @version 1.0
 * @description:������Ӧ��-������Դ��Ϣ Model�� 
 */
 class model_outsourcing_outsourcessupp_vehiclesuppequ  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcessupp_vehiclesuppequ";
		$this->sql_map = "outsourcing/outsourcessupp/vehiclesuppequSql.php";
		parent::__construct ();
	}     
 }
?>
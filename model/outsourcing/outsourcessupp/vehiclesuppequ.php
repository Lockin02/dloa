<?php
/**
 * @author Show
 * @Date 2014年1月7日 星期二 9:21:37
 * @version 1.0
 * @description:车辆供应商-车辆资源信息 Model层 
 */
 class model_outsourcing_outsourcessupp_vehiclesuppequ  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcessupp_vehiclesuppequ";
		$this->sql_map = "outsourcing/outsourcessupp/vehiclesuppequSql.php";
		parent::__construct ();
	}     
 }
?>
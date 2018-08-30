<?php
/**
 * @author Michael
 * @Date 2014年3月24日 星期一 14:50:04
 * @version 1.0
 * @description:租车合同租赁车辆信息 Model层
 */
 class model_outsourcing_contract_vehicle  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_vehicle";
		$this->sql_map = "outsourcing/contract/vehicleSql.php";
		parent::__construct ();
	}
 }
?>
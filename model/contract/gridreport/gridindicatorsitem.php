<?php
/**
 * @author yxin1
 * @Date 2014年12月1日 13:43:22
 * @version 1.0
 * @description:表格指标表明细 Model层 
 */
 class model_contract_gridreport_gridindicatorsitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_system_gridindicators_item";
		$this->sql_map = "contract/gridreport/gridindicatorsitemSql.php";
		parent::__construct ();
	}     
 }
?>
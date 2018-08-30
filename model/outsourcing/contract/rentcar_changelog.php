<?php
/**
 * @author Show
 * @Date 2014年3月6日 星期四 10:13:09
 * @version 1.0
 * @description:租车同变更记录表 Model层 
 */
 class model_outsourcing_contract_rentcar_changelog  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_rentcar_changelog";
		$this->sql_map = "outsourcing/contract/rentcar_changelogSql.php";
		parent::__construct ();
	}     
 }
?>
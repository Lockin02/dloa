<?php
/**
 * @author Administrator
 * @Date 2012-10-24 15:52:02
 * @version 1.0
 * @description:合同信息备注 Model层
 */
 class model_contract_contract_remark  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_remark";
		$this->sql_map = "contract/contract/remarkSql.php";
		parent::__construct ();
	}     }
?>
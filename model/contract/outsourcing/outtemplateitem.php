<?php
/**
 * @author Show
 * @Date 2013年10月8日 0:20:42
 * @version 1.0
 * @description:外包模板费用模板明细 Model层 
 */
 class model_contract_outsourcing_outtemplateitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_outsourcing_templateitem";
		$this->sql_map = "contract/outsourcing/outtemplateitemSql.php";
		parent::__construct ();
	}     
 }
?>
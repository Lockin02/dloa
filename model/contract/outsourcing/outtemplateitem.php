<?php
/**
 * @author Show
 * @Date 2013��10��8�� 0:20:42
 * @version 1.0
 * @description:���ģ�����ģ����ϸ Model�� 
 */
 class model_contract_outsourcing_outtemplateitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_outsourcing_templateitem";
		$this->sql_map = "contract/outsourcing/outtemplateitemSql.php";
		parent::__construct ();
	}     
 }
?>
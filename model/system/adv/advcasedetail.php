<?php
/**
 *�߼�����������ϸmodel��
 */
 class model_system_adv_advcasedetail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_adv_case_detail";
		$this->sql_map = "system/adv/advcasedetailSql.php";
		parent::__construct ();
	}


 }
?>
<?php
/**
 * @description:������Ŀ����ë���ʼ�¼��(oa_esm_records_exgross) Model��
 */
class model_engineering_project_records_exgross extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_records_exgross";
		$this->sql_map = "engineering/project/records/exgrossSql.php";
		parent::__construct ();
	}
  
}
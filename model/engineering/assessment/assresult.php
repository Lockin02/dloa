<?php
/**
 * @author huangzf
 * @Date 2010��12��9�� 20:09:03
 * @version 1.0
 * @description:����ָ������¼�� Model��
 */
 class model_engineering_assessment_assresult  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_ass_result";
		$this->sql_map = "log/assessment/eassresultSql.php";
		parent::__construct ();
	}
 }
?>
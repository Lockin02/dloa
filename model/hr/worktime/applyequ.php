<?php
/**
 * @author yxin1
 * @Date 2014��4��24�� 13:37:38
 * @version 1.0
 * @description:�ڼ��ռӰ�������ϸ�� Model�� 
 */
 class model_hr_worktime_applyequ  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_worktime_applyequ";
		$this->sql_map = "hr/worktime/applyequSql.php";
		parent::__construct ();
	}     
 }
?>
<?php
/**
 * @author huangzf
 * @Date 2011��12��3�� 10:34:08
 * @version 1.0
 * @description:�豸�����嵥 Model�� 
 */
class model_service_change_changeitem extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_service_change_item";
		$this->sql_map = "service/change/changeitemSql.php";
		parent::__construct ();
	}
}
?>
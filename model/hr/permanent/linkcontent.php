<?php
/**
 * @author Administrator
 * @Date 2012��8��6�� 21:39:29
 * @version 1.0
 * @description:Ա��ת�����˹������ Model�� 
 */
 class model_hr_permanent_linkcontent  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_permanent_linkcontent";
		$this->sql_map = "hr/permanent/linkcontentSql.php";
		parent::__construct ();
	}     
 }
?>
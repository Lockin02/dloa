<?php
/**
 * @author Administrator
 * @Date 2012��8��22�� 19:39:28
 * @version 1.0
 * @description:��ʦ���˱�ģ�� Model�� 
 */
 class model_hr_tutor_schemeTemplate  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_schemeTemplate";
		$this->sql_map = "hr/tutor/schemeTemplateSql.php";
		parent::__construct ();
	}     
 }
?>
<?php
/**
 * @author Show
 * @Date 2012��8��21�� ���ڶ� 10:12:31
 * @version 1.0
 * @description:��ְ�ʸ�ģ����ϸ Model��
 */
 class model_hr_baseinfo_certifytemplatedetail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_baseinfo_certifytemplate_detail";
		$this->sql_map = "hr/baseinfo/certifytemplatedetailSql.php";
		parent::__construct ();
	}
}
?>
<?php
/**
 * @author Show
 * @Date 2012年8月21日 星期二 10:12:31
 * @version 1.0
 * @description:任职资格模板明细 Model层
 */
 class model_hr_baseinfo_certifytemplatedetail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_baseinfo_certifytemplate_detail";
		$this->sql_map = "hr/baseinfo/certifytemplatedetailSql.php";
		parent::__construct ();
	}
}
?>
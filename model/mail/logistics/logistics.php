<?php
/**
 * @author Show
 * @Date 2011年5月27日 星期五 9:35:54
 * @version 1.0
 * @description:物流公司基本信息 Model层
 */
class model_mail_logistics_logistics extends model_base {

	function __construct() {
		$this->tbl_name = "oa_mail_logistics";
		$this->sql_map = "mail/logistics/logisticsSql.php";
		parent :: __construct();
	}

	//返回是否
	function rtYesOrNo($thisVal){
		if($thisVal == 1){
			return '是';
		}else{
			return '否';
		}
	}
}
?>
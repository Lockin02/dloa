<?php
/**
 * @author Show
 * @Date 2011��5��27�� ������ 9:35:54
 * @version 1.0
 * @description:������˾������Ϣ Model��
 */
class model_mail_logistics_logistics extends model_base {

	function __construct() {
		$this->tbl_name = "oa_mail_logistics";
		$this->sql_map = "mail/logistics/logisticsSql.php";
		parent :: __construct();
	}

	//�����Ƿ�
	function rtYesOrNo($thisVal){
		if($thisVal == 1){
			return '��';
		}else{
			return '��';
		}
	}
}
?>
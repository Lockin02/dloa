<?php
/**
 * @author Show
 * @Date 2011��12��27�� ���ڶ� 20:38:02
 * @version 1.0
 * @description:Ӧ��������Ʊ��ϸ Model��
 */
class model_finance_invother_invotherdetail extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_invother_detail";
		$this->sql_map = "finance/invother/invotherdetailSql.php";
		parent :: __construct();
	}
}
?>
<?php
/**
 * @author Administrator
 * @Date 2010��12��21�� 21:00:18
 * @version 1.0
 * @description:�̵�����嵥 Model��
 */
 class model_stock_checkinfo_stockinstocklist  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_instock_list";
		$this->sql_map = "stock/checkinfo/stockinstocklistSql.php";
		parent::__construct ();
	}
	/**
	 * ����checkidɾ����Ʒ��Ϣ
	 */
	function deleteByHardWareId($checkId) {
		$conditions = array (
			"checkId" => $checkId
		);
		parent :: delete($conditions);
	}
 }
?>
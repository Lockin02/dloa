<?php
/**
 * @author huangzf
 * @Date 2012��6��1�� ������ 16:54:00
 * @version 1.0
 * @description:��Ʒ���Ͽ��ɹ������ۺϱ��嵥 Model�� 
 */
 class model_stock_extra_procompositebaseitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_extra_procompositeitem";
		$this->sql_map = "stock/extra/procompositebaseitemSql.php";
		parent::__construct ();
	}     
 }
?>
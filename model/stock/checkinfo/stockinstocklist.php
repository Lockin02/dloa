<?php
/**
 * @author Administrator
 * @Date 2010年12月21日 21:00:18
 * @version 1.0
 * @description:盘点入库清单 Model层
 */
 class model_stock_checkinfo_stockinstocklist  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_instock_list";
		$this->sql_map = "stock/checkinfo/stockinstocklistSql.php";
		parent::__construct ();
	}
	/**
	 * 根据checkid删除产品信息
	 */
	function deleteByHardWareId($checkId) {
		$conditions = array (
			"checkId" => $checkId
		);
		parent :: delete($conditions);
	}
 }
?>
<?php
/**
 * @author huangzf
 * @Date 2011��5��15�� 13:39:03
 * @version 1.0
 * @description:���ⵥ�����嵥 Model�� 
 */
 class model_stock_outstock_stockoutitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_outstock_item";
		$this->sql_map = "stock/outstock/stockoutitemSql.php";
		parent::__construct ();
	}
	
 	/**
	 * ���ݻ���������ϢID��ȡ�嵥��Ϣ
	 * @author huangzf
	 */
	function getItemByMainId($mainId){
			$this->searchArr['mainId']=$mainId;
			return $this->listBySqlId();
	}
 }
?>
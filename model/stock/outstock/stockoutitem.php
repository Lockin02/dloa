<?php
/**
 * @author huangzf
 * @Date 2011年5月15日 13:39:03
 * @version 1.0
 * @description:出库单物料清单 Model层 
 */
 class model_stock_outstock_stockoutitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_outstock_item";
		$this->sql_map = "stock/outstock/stockoutitemSql.php";
		parent::__construct ();
	}
	
 	/**
	 * 根据基本基本信息ID获取清单信息
	 * @author huangzf
	 */
	function getItemByMainId($mainId){
			$this->searchArr['mainId']=$mainId;
			return $this->listBySqlId();
	}
 }
?>
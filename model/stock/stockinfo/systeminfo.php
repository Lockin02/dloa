<?php
/**
 * @author Administrator
 * @Date 2011年5月28日 16:50:58
 * @version 1.0
 * @description:仓存管理基础信息设置 Model层
 */
 class model_stock_stockinfo_systeminfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_syteminfo";
		$this->sql_map = "stock/stockinfo/systeminfoSql.php";
		parent::__construct ();
	}

	/**
	 * 根据类型获取默认仓库
	 */
	 function getStockByType_d($type){
	 	$rows = $this->get_d(1);
		if( $type=='oa_borrow_borrow' ){
			return array(
				'stockId'=>$rows['outStockId'],
				'stockName'=>$rows['outStockName'],
				'stockCode'=>$rows['outStockCode']
			);
		}else{
			return array(
				'stockId'=>$rows['salesStockId'],
				'stockName'=>$rows['salesStockName'],
				'stockCode'=>$rows['salesStockCode']
			);
		}
	 }

 }
?>
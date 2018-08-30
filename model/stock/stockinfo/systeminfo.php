<?php
/**
 * @author Administrator
 * @Date 2011��5��28�� 16:50:58
 * @version 1.0
 * @description:�ִ���������Ϣ���� Model��
 */
 class model_stock_stockinfo_systeminfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_syteminfo";
		$this->sql_map = "stock/stockinfo/systeminfoSql.php";
		parent::__construct ();
	}

	/**
	 * �������ͻ�ȡĬ�ϲֿ�
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
<?php
/**
 * @author huangzf
 * @Date 2011年5月18日 10:50:36
 * @version 1.0
 * @description:物料批次号台账 Model层 
 */
 class model_stock_batchno_batchno  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_product_batchno";
		$this->sql_map = "stock/batchno/batchnoSql.php";
		parent::__construct ();
	}
 	/**
	 * 
	 * 根据入库清单id查找批次号
	 * @param  $inDocItemId
	 */
	function findByInItemId($inDocItemId){
			$this->searchArr['inDocItemId']=$inDocItemId;
			return $this->listBySqlId();		
	}
 }
?>
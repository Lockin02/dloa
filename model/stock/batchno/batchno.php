<?php
/**
 * @author huangzf
 * @Date 2011��5��18�� 10:50:36
 * @version 1.0
 * @description:�������κ�̨�� Model�� 
 */
 class model_stock_batchno_batchno  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_product_batchno";
		$this->sql_map = "stock/batchno/batchnoSql.php";
		parent::__construct ();
	}
 	/**
	 * 
	 * ��������嵥id�������κ�
	 * @param  $inDocItemId
	 */
	function findByInItemId($inDocItemId){
			$this->searchArr['inDocItemId']=$inDocItemId;
			return $this->listBySqlId();		
	}
 }
?>
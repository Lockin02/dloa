<?php
/**
 * @author liub
 * @Date 2012-04-07 14:03:40
 * @version 1.0
 * @description:换货退货物料清单 Model层
 */
 class model_projectmanagent_exchange_exchangebackequ extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_exchange_backequ";
		$this->sql_map = "projectmanagent/exchange/exchangebackequSql.php";
		parent::__construct ();
	}
   	/**
	 * 根据项目id获取产品列表
	 */
	function getDetail_d($exchangeId) {
		$this->searchArr['exchangeId'] = $exchangeId;
		return $this->list_d();
	}
	
	/**
	 * 换货需求下达收货通知单时，获取未下达的明细id
	 */
	function getEquIdByExchangeId_d($exchangeId) {
		$sql = "SELECT e.id,
		        (e.number - e.issuedBackNum) AS remainNum
		        FROM
		              $this->tbl_name e
		        WHERE
		              e.exchangeId = $exchangeId";
		$rs = $this->_db->getArray( $sql );
		return $rs;
	}
}
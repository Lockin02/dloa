<?php
/**
 * @author liub
 * @Date 2012-04-07 14:03:40
 * @version 1.0
 * @description:�����˻������嵥 Model��
 */
 class model_projectmanagent_exchange_exchangebackequ extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_exchange_backequ";
		$this->sql_map = "projectmanagent/exchange/exchangebackequSql.php";
		parent::__construct ();
	}
   	/**
	 * ������Ŀid��ȡ��Ʒ�б�
	 */
	function getDetail_d($exchangeId) {
		$this->searchArr['exchangeId'] = $exchangeId;
		return $this->list_d();
	}
	
	/**
	 * ���������´��ջ�֪ͨ��ʱ����ȡδ�´����ϸid
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
<?php
/**
 * @author Administrator
 * @Date 2012��5��23�� 9:50:17
 * @version 1.0
 * @description:���������Զ������� Model��
 */
 class model_projectmanagent_shipment_shipmenttype  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_shipment_type";
		$this->sql_map = "projectmanagent/shipment/shipmenttypeSql.php";
		parent::__construct ();
	}


	/**
	 * ����ɾ������
	 */
	function deletes_d($ids) {
		try {
			$this->deletes ( $ids );
			$allsourceDao = new model_common_contract_allsource();
			$flag = $allsourceDao->clearCusTypeByIds($ids);
			return $flag;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}
 }
?>
<?php
/**
 * @author Administrator
 * @Date 2012年5月23日 9:50:17
 * @version 1.0
 * @description:发货需求自定义类型 Model层
 */
 class model_projectmanagent_shipment_shipmenttype  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_shipment_type";
		$this->sql_map = "projectmanagent/shipment/shipmenttypeSql.php";
		parent::__construct ();
	}


	/**
	 * 批量删除对象
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
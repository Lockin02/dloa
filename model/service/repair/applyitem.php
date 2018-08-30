<?php
/**
 * @author huangzf
 * @Date 2011年12月1日 14:23:25
 * @version 1.0
 * @description:维修申请(报价)清单 Model层
 */
class model_service_repair_applyitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_service_repair_applyitem";
		$this->sql_map = "service/repair/applyitemSql.php";
		parent::__construct ();
	}

	/**
	 *
	 * 取消已确认报价清单
	 */
	function cancelQuote_d($id) {
		$applyitemobj = $this->get_d ( $id );
		$applyitemobj ['isQuote'] = "0";
		return $this->updateById ( $applyitemobj );
	}
}
?>
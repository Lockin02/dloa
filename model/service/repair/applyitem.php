<?php
/**
 * @author huangzf
 * @Date 2011��12��1�� 14:23:25
 * @version 1.0
 * @description:ά������(����)�嵥 Model��
 */
class model_service_repair_applyitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_service_repair_applyitem";
		$this->sql_map = "service/repair/applyitemSql.php";
		parent::__construct ();
	}

	/**
	 *
	 * ȡ����ȷ�ϱ����嵥
	 */
	function cancelQuote_d($id) {
		$applyitemobj = $this->get_d ( $id );
		$applyitemobj ['isQuote'] = "0";
		return $this->updateById ( $applyitemobj );
	}
}
?>
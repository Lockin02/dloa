<?php
/**
 * @author Administrator
 * @Date 2013年11月11日 星期一 22:21:40
 * @version 1.0
 * @description:备货申请明细表 Model层 
 */
 class model_stockup_apply_applyProducts  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stockup_apply_products";
		$this->sql_map = "stockup/apply/applyProductsSql.php";
		parent::__construct ();
	}
	
	/**
	 * 更新状态
	 */
	function updateObjStatus($id, $statusType,$statusValue) {
		if ($id && $statusType&&$statusValue) {
			$sql = "UPDATE  oa_stockup_apply_products SET $statusType='$statusValue' WHERE id='$id'; ";
			$flag = $this->query($sql);
		}
		return $flag;
	}
	
 }
?>
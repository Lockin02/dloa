<?php
/**
 * @author Administrator
 * @Date 2013年11月11日 星期一 22:22:42
 * @version 1.0
 * @description:产品备货明细表 Model层 
 */
 class model_stockup_application_applicationProducts  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stockup_application_products";
		$this->sql_map = "stockup/application/applicationProductsSql.php";
		parent::__construct ();
	}
	
	/**
	 * 更新状态
	 */
	function updateObjStatus($id, $statusType,$statusValue) {
		if ($id && $statusType&&$statusValue) {
			$sql = "UPDATE  oa_stockup_application SET $statusType='$statusValue' WHERE id='$id'; ";
			$flag = $this->query($sql);
		}
		return $flag;
	}
	function getProductInfo($listId){
		$sqlStr="SELECT * FROM  oa_stockup_application_products a 
				 WHERE a.listId='$listId'";
		$rs = $this->_db->getArray($sqlStr);
		return $rs;
	}
	     
 }
?>
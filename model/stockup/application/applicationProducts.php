<?php
/**
 * @author Administrator
 * @Date 2013��11��11�� ����һ 22:22:42
 * @version 1.0
 * @description:��Ʒ������ϸ�� Model�� 
 */
 class model_stockup_application_applicationProducts  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stockup_application_products";
		$this->sql_map = "stockup/application/applicationProductsSql.php";
		parent::__construct ();
	}
	
	/**
	 * ����״̬
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
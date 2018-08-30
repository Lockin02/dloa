<?php
/**
 *初始化借试用与调拨单关联
 */
class model_projectmanagent_borrow_borrowRefAllocat extends model_base {

	function __construct() {
		$this->tbl_name = "oa_borrow_r_allocat";
		$this->sql_map = "projectmanagent/borrow/borrowRefAllocatSql.php";
		parent::__construct ();
	}

	/**
	 *
	 * 根据申请单id获取关联的归还调拨单id
	 * @param $applyId
	 */
	function getAllocatIdArr($applyId) {
		$rows = $this->findAll ( array ("borrowId" => $applyId ) );
		$idArr = array ("-1");
		if (is_array ( $rows )) {
			foreach ( $rows as $key => $value ) {
				array_push ( $idArr, $value ['allocatId'] );
			}
		}
		return $idArr;
	}

}
?>
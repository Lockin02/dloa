<?php
/**
 *
 * 费用分摊控制层（用于其他合同审批前临时存放）
 * @author chenrf
 *
 */
class controller_contract_other_payablescost extends controller_base_action {
	function __construct() {
		$this->objName = "payablescost";
		$this->objPath = "contract_other";
		parent :: __construct();
	}

	/**
	 *
	 * 查询分摊费用明细（包括临时导入信息）
	 */
	function c_listCost() {
		$rows = $this->service->listCost($_REQUEST['payapplyId'], $_SESSION['USER_ID']);
		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil :: encode($rows);
	}
}
?>
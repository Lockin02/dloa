<?php
/**
 *
 * ���÷�̯���Ʋ㣨����������ͬ����ǰ��ʱ��ţ�
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
	 * ��ѯ��̯������ϸ��������ʱ������Ϣ��
	 */
	function c_listCost() {
		$rows = $this->service->listCost($_REQUEST['payapplyId'], $_SESSION['USER_ID']);
		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil :: encode($rows);
	}
}
?>
<?php
/**
 * @author Show
 * @Date 2013��10��8�� 0:20:42
 * @version 1.0
 * @description:���ģ�����ģ����Ʋ�
 */
class controller_contract_outsourcing_outtemplate extends controller_base_action {

	function __construct() {
		$this->objName = "outtemplate";
		$this->objPath = "contract_outsourcing";
		parent :: __construct();
	}

	//����ģ��
	function c_saveTemplate(){
		$itemArr = util_jsonUtil::iconvUTF2GBArr($_POST['obj']);
		exit($this->service->saveTemplate_d($itemArr));
	}
}
?>
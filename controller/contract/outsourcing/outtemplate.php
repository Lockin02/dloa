<?php
/**
 * @author Show
 * @Date 2013年10月8日 0:20:42
 * @version 1.0
 * @description:外包模板费用模板控制层
 */
class controller_contract_outsourcing_outtemplate extends controller_base_action {

	function __construct() {
		$this->objName = "outtemplate";
		$this->objPath = "contract_outsourcing";
		parent :: __construct();
	}

	//保存模板
	function c_saveTemplate(){
		$itemArr = util_jsonUtil::iconvUTF2GBArr($_POST['obj']);
		exit($this->service->saveTemplate_d($itemArr));
	}
}
?>
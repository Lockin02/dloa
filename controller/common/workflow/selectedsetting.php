<?php
/**
 * @author Show
 * @Date 2012年3月6日 星期二 14:02:58
 * @version 1.0
 * @description:工作流选择设定表控制层
 */
class controller_common_workflow_selectedsetting extends controller_base_action {

	function __construct() {
		$this->objName = "selectedsetting";
		$this->objPath = "common_workflow";
		parent::__construct ();
	}

	/**
	 * 更新用户选择编码
	 */
	function c_changeSelectedCode(){
		$selectedCode = util_jsonUtil :: iconvUTF2GB($_POST['selectedCode']);
		$gridId = util_jsonUtil :: iconvUTF2GB($_POST['gridId']);
		$this->service->update(array('userId' => $_SESSION['USER_ID'] , 'gridId' => $gridId) , array('selectedCode' => $selectedCode));
	}
}
?>
<?php
/**
 * @author Show
 * @Date 2012��3��6�� ���ڶ� 14:02:58
 * @version 1.0
 * @description:������ѡ���趨����Ʋ�
 */
class controller_common_workflow_selectedsetting extends controller_base_action {

	function __construct() {
		$this->objName = "selectedsetting";
		$this->objPath = "common_workflow";
		parent::__construct ();
	}

	/**
	 * �����û�ѡ�����
	 */
	function c_changeSelectedCode(){
		$selectedCode = util_jsonUtil :: iconvUTF2GB($_POST['selectedCode']);
		$gridId = util_jsonUtil :: iconvUTF2GB($_POST['gridId']);
		$this->service->update(array('userId' => $_SESSION['USER_ID'] , 'gridId' => $gridId) , array('selectedCode' => $selectedCode));
	}
}
?>
<?php 
class controller_system_usersetting_usersetting extends controller_base_action {

	function __construct() {
		$this->objName = "usersetting";
		$this->objPath = "system_usersetting";
		parent::__construct ();
	}
	
	function c_update(){
		$condition = array('user' => $_POST['user'],'businessCode' => 'esmreport');
		$this->service->update($condition,array('memoryused'=>util_jsonUtil::encode($_POST['memoryused'])));
	} 
}
?>
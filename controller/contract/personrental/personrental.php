<?php
/**
 * @author liangjj
 * @Date 2013��9��22�� 14:30:28
 * @version 1.0
 * @description:�����ͬ��Ա���޿��Ʋ�
 */
class controller_contract_personrental_personrental extends controller_base_action {

	function __construct() {
		$this->objName = "personrental";
		$this->objPath = "contract_personrental";
		parent :: __construct();
	}
	
	function c_page(){
		$this->view('list');
	}
}
?>
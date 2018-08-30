<?php
/**
 * @author liangjj
 * @Date 2013年9月22日 14:30:28
 * @version 1.0
 * @description:外包合同人员租赁控制层
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
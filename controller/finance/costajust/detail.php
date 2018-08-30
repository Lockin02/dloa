<?php
/**
 * @author Show
 * @Date 2011年5月31日 星期二 10:30:26
 * @version 1.0
 * @description:成本调整单详细控制层 
 */
class controller_finance_costajust_detail extends controller_base_action {

	function __construct() {
		$this->objName = "detail";
		$this->objPath = "finance_costajust";
		parent::__construct ();
	}
    
	/*
	 * 跳转到成本调整单详细
	 */
    function c_page() {
       $this->display('list');
    }
}
?>
<?php
/**
 * @author Show
 * @Date 2011��5��31�� ���ڶ� 10:30:26
 * @version 1.0
 * @description:�ɱ���������ϸ���Ʋ� 
 */
class controller_finance_costajust_detail extends controller_base_action {

	function __construct() {
		$this->objName = "detail";
		$this->objPath = "finance_costajust";
		parent::__construct ();
	}
    
	/*
	 * ��ת���ɱ���������ϸ
	 */
    function c_page() {
       $this->display('list');
    }
}
?>
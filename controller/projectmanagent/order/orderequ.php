<?php
/**
 * @author Administrator
 * @Date 2011��3��4�� 10:42:18
 * @version 1.0
 * @description:������Ʒ�嵥���Ʋ� 
 */
class controller_projectmanagent_order_orderequ extends controller_base_action {

	function __construct() {
		$this->objName = "orderequ";
		$this->objPath = "projectmanagent_order";
		parent::__construct ();
	 }
    
	/*
	 * ��ת��������Ʒ�嵥
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
<?php
/**
 * @author Administrator
 * @Date 2011��3��4�� 15:06:47
 * @version 1.0
 * @description:������Ʊ�ƻ����Ʋ� 
 */
class controller_projectmanagent_order_invoice extends controller_base_action {

	function __construct() {
		$this->objName = "invoice";
		$this->objPath = "projectmanagent_order";
		parent::__construct ();
	 }
    
	/*
	 * ��ת��������Ʊ�ƻ�
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
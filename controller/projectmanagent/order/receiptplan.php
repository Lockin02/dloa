<?php
/**
 * @author Administrator
 * @Date 2011��3��4�� 15:13:34
 * @version 1.0
 * @description:�����տ�ƻ����Ʋ� 
 */
class controller_projectmanagent_order_receiptplan extends controller_base_action {

	function __construct() {
		$this->objName = "receiptplan";
		$this->objPath = "projectmanagent_order";
		parent::__construct ();
	 }
    
	/*
	 * ��ת�������տ�ƻ�
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
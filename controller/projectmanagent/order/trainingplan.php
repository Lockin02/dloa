<?php
/**
 * @author Administrator
 * @Date 2011��3��4�� 15:14:00
 * @version 1.0
 * @description:������ѵ�ƻ����Ʋ� 
 */
class controller_projectmanagent_order_trainingplan extends controller_base_action {

	function __construct() {
		$this->objName = "trainingplan";
		$this->objPath = "projectmanagent_order";
		parent::__construct ();
	 }
    
	/*
	 * ��ת��������ѵ�ƻ�
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
<?php
/**
 * @author Administrator
 * @Date 2011��5��9�� 15:19:15
 * @version 1.0
 * @description:����������ѵ�ƻ����Ʋ� 
 */
class controller_projectmanagent_borrow_trainingplan extends controller_base_action {

	function __construct() {
		$this->objName = "trainingplan";
		$this->objPath = "projectmanagent_borrow";
		parent::__construct ();
	 }
    
	/*
	 * ��ת������������ѵ�ƻ�
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
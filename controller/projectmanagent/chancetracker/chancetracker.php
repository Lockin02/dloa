<?php
/**
 * @author Administrator
 * @Date 2011��3��4�� 14:56:13
 * @version 1.0
 * @description:�̻������˿��Ʋ� 
 */
class controller_projectmanagent_chancetracker_chancetracker extends controller_base_action {

	function __construct() {
		$this->objName = "chancetracker";
		$this->objPath = "projectmanagent_chancetracker";
		parent::__construct ();
	 }
    
	/*
	 * ��ת���̻�������
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
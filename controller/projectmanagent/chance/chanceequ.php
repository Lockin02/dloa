<?php
/**
 * @author Administrator
 * @Date 2011��3��16�� 9:34:46
 * @version 1.0
 * @description:�̻���Ʒ�嵥���Ʋ� 
 */
class controller_projectmanagent_chance_chanceequ extends controller_base_action {

	function __construct() {
		$this->objName = "chanceequ";
		$this->objPath = "projectmanagent_chance";
		parent::__construct ();
	 }
    
	/*
	 * ��ת���̻���Ʒ�嵥
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
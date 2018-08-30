<?php
/**
 * @author Administrator
 * @Date 2011年5月9日 15:19:15
 * @version 1.0
 * @description:借用申请培训计划控制层 
 */
class controller_projectmanagent_borrow_trainingplan extends controller_base_action {

	function __construct() {
		$this->objName = "trainingplan";
		$this->objPath = "projectmanagent_borrow";
		parent::__construct ();
	 }
    
	/*
	 * 跳转到借用申请培训计划
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
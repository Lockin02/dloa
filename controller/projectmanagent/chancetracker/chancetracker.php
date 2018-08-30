<?php
/**
 * @author Administrator
 * @Date 2011年3月4日 14:56:13
 * @version 1.0
 * @description:商机跟踪人控制层 
 */
class controller_projectmanagent_chancetracker_chancetracker extends controller_base_action {

	function __construct() {
		$this->objName = "chancetracker";
		$this->objPath = "projectmanagent_chancetracker";
		parent::__construct ();
	 }
    
	/*
	 * 跳转到商机跟踪人
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
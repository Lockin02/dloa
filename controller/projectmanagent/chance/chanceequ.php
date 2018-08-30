<?php
/**
 * @author Administrator
 * @Date 2011年3月16日 9:34:46
 * @version 1.0
 * @description:商机产品清单控制层 
 */
class controller_projectmanagent_chance_chanceequ extends controller_base_action {

	function __construct() {
		$this->objName = "chanceequ";
		$this->objPath = "projectmanagent_chance";
		parent::__construct ();
	 }
    
	/*
	 * 跳转到商机产品清单
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
<?php
/**
 * @author
 * @version 1.0
 * @description:商机联系人控制层
 */
class controller_projectmanagent_chance_linkman extends controller_base_action {

	function __construct() {
		$this->objName = "linkman";
		$this->objPath = "projectmanagent_chance";
		parent::__construct ();
	 }

	/*
	 * 跳转到线索跟踪人
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }



 }
?>
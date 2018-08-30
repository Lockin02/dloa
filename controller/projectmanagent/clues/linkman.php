<?php
/**
 * @author suxc
 * @version 1.0
 * @description:线索联系人控制层
 */
class controller_projectmanagent_clues_linkman extends controller_base_action {

	function __construct() {
		$this->objName = "linkman";
		$this->objPath = "projectmanagent_clues";
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
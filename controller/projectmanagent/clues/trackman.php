<?php
/**
 * @author Administrator
 * @Date 2011年3月5日 10:20:18
 * @version 1.0
 * @description:线索跟踪人控制层
 */
class controller_projectmanagent_clues_trackman extends controller_base_action {

	function __construct() {
		$this->objName = "trackman";
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
<?php
/**
 * @author Administrator
 * @Date 2011年5月8日 14:16:25
 * @version 1.0
 * @description:研发合同培训计划控制层
 */
class controller_rdproject_yxrdproject_trainingplan extends controller_base_action {

	function __construct() {
		$this->objName = "trainingplan";
		$this->objPath = "rdproject_yxrdproject";
		parent::__construct ();
	 }

	/*
	 * 跳转到研发合同培训计划
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
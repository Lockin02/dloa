<?php
/**
 * @author Administrator
 * @Date 2011年5月5日 10:16:50
 * @version 1.0
 * @description:服务合同培训计划控制层
 */
class controller_engineering_serviceContract_trainingplan extends controller_base_action {

	function __construct() {
		$this->objName = "trainingplan";
		$this->objPath = "engineering_serviceContract";
		parent::__construct ();
	 }

	/*
	 * 跳转到服务合同培训计划
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
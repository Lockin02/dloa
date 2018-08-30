<?php
/**
 * @author Administrator
 * @Date 2011年5月5日 14:48:29
 * @version 1.0
 * @description:租借合同培训计划控制层
 */
class controller_contract_rental_trainingplan extends controller_base_action {

	function __construct() {
		$this->objName = "trainingplan";
		$this->objPath = "contract_rental";
		parent::__construct ();
	 }

	/*
	 * 跳转到租借合同培训计划
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
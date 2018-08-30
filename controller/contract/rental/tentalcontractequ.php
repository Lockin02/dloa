<?php
/**
 * @author Administrator
 * @Date 2011年5月5日 14:44:17
 * @version 1.0
 * @description:租借产品清单控制层
 */
class controller_contract_rental_tentalcontractequ extends controller_base_action {

	function __construct() {
		$this->objName = "tentalcontractequ";
		$this->objPath = "contract_rental";
		parent::__construct ();
	 }

	/*
	 * 跳转到租借产品清单
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
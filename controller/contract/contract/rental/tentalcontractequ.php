<?php
/**
 * @author Administrator
 * @Date 2011��5��5�� 14:44:17
 * @version 1.0
 * @description:����Ʒ�嵥���Ʋ�
 */
class controller_contract_rental_tentalcontractequ extends controller_base_action {

	function __construct() {
		$this->objName = "tentalcontractequ";
		$this->objPath = "contract_rental";
		parent::__construct ();
	 }

	/*
	 * ��ת������Ʒ�嵥
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
<?php
/**
 * @author Administrator
 * @Date 2011��5��5�� 14:48:29
 * @version 1.0
 * @description:����ͬ��ѵ�ƻ����Ʋ�
 */
class controller_contract_rental_trainingplan extends controller_base_action {

	function __construct() {
		$this->objName = "trainingplan";
		$this->objPath = "contract_rental";
		parent::__construct ();
	 }

	/*
	 * ��ת������ͬ��ѵ�ƻ�
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
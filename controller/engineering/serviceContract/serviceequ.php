<?php
/**
 * @author Administrator
 * @Date 2011��5��5�� 10:02:07
 * @version 1.0
 * @description:�����ͬ�����嵥���Ʋ�
 */
class controller_engineering_serviceContract_serviceequ extends controller_base_action {

	function __construct() {
		$this->objName = "serviceequ";
		$this->objPath = "engineering_serviceContract";
		parent::__construct ();
	 }

	/*
	 * ��ת�������ͬ�����嵥
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
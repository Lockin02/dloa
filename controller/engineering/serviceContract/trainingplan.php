<?php
/**
 * @author Administrator
 * @Date 2011��5��5�� 10:16:50
 * @version 1.0
 * @description:�����ͬ��ѵ�ƻ����Ʋ�
 */
class controller_engineering_serviceContract_trainingplan extends controller_base_action {

	function __construct() {
		$this->objName = "trainingplan";
		$this->objPath = "engineering_serviceContract";
		parent::__construct ();
	 }

	/*
	 * ��ת�������ͬ��ѵ�ƻ�
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
<?php
/**
 * @author Show
 * @Date 2011��1��13�� ������ 17:22:31
 * @version 1.0
 * @description:�����Ŀ���Ʋ�
 */
class controller_finance_adjust_adjustdetail extends controller_base_action {

	function __construct() {
		$this->objName = "adjustdetail";
		$this->objPath = "finance_adjust";
		parent::__construct ();
	 }

	/*
	 * ��ת�������Ŀ
	 */
    function c_page() {
      $this->display('list');
    }
 }
?>
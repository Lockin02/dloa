<?php
/**
 * @author Show
 * @Date 2011��3��4�� ������ 10:07:57
 * @version 1.0
 * @description:����Ʊ��ȼ�¼����Ʋ�
 */
class controller_finance_invoice_yearPlan extends controller_base_action {

	function __construct() {
		$this->objName = "yearPlan";
		$this->objPath = "finance_invoice";
		parent::__construct ();
	 }

	/*
	 * ��ת������Ʊ��ȼ�¼��
	 */
    function c_page() {
      $this->display('list');
    }

    /**
     * ��дtoAdd
     */
	function c_toAdd(){
		$this->assign('year',date('Y'));
		$this->display('add');
	}
 }
?>
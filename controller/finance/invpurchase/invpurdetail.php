<?php
/**
 * @author Show
 * @Date 2010��12��21�� ���ڶ� 15:54:46
 * @version 1.0
 * @description:�ɹ���Ʊ��Ŀ���Ʋ� 
 */
class controller_finance_invpurchase_invpurdetail extends controller_base_action {

	function __construct() {
		$this->objName = "invpurdetail";
		$this->objPath = "finance_invpurchase";
		parent::__construct ();
	 }
    
	/*
	 * ��ת���ɹ���Ʊ��Ŀ
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
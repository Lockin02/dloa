<?php
/**
 * @author Administrator
 * @Date 2010��12��21�� 21:00:18
 * @version 1.0
 * @description:�̵�����嵥���Ʋ� 
 */
class controller_stock_checkinfo_stockinstocklist extends controller_base_action {

	function __construct() {
		$this->objName = "stockinstocklist";
		$this->objPath = "stock_checkinfo";
		parent::__construct ();
	 }
    
	/*
	 * ��ת���̵�����嵥
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
<?php
/**
 * @author Administrator
 * @Date 2011��5��18�� 10:50:36
 * @version 1.0
 * @description:�������κ�̨�˿��Ʋ� 
 */
class controller_stock_batchno_batchno extends controller_base_action {

	function __construct() {
		$this->objName = "batchno";
		$this->objPath = "stock_batchno";
		parent::__construct ();
	 }
    
	/*
	 * ��ת���������κ�̨��
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
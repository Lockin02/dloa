<?php
/**
 * @author Administrator
 * @Date 2011��8��9�� 19:38:28
 * @version 1.0
 * @description:�̵������嵥���Ʋ� 
 */
class controller_stock_check_checkitem extends controller_base_action {

	function __construct() {
		$this->objName = "checkitem";
		$this->objPath = "stock_check";
		parent::__construct ();
	 }
    
	/*
	 * ��ת���̵������嵥
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
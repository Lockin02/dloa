<?php
/**
 * @author Administrator
 * @Date 2011��6��2�� 10:30:23
 * @version 1.0
 * @description:���������嵥���Ʋ� 
 */
class controller_produce_protask_protaskequ extends controller_base_action {

	function __construct() {
		$this->objName = "protaskequ";
		$this->objPath = "produce_protask";
		parent::__construct ();
	 }
    
	/*
	 * ��ת�����������嵥
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
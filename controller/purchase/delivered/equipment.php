<?php
/**
 * @author suxc
 * @Date 2011��5��6�� 10:27:57
 * @version 1.0
 * @description:����֪ͨ�������嵥��Ϣ���Ʋ� 
 */
class controller_purchase_delivered_equipment extends controller_base_action {

	function __construct() {
		$this->objName = "equipment";
		$this->objPath = "purchase_delivered";
		parent::__construct ();
	 }
    
	/*
	 * ��ת������֪ͨ�������嵥��Ϣ
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
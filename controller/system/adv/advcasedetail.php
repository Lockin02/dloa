<?php
/**
 * �߼���������ϸ���Ʋ�
 */
class controller_system_adv_advcasedetail extends controller_base_action {

	function __construct() {
		$this->objName = "advcasedetail";
		$this->objPath = "system_adv";
		parent::__construct ();
	 }


	 /**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJson() {
		$this->service->asc=false;
		return parent::c_listJson();
	}


 }
?>
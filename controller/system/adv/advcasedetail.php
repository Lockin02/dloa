<?php
/**
 * 高级搜索方案细控制层
 */
class controller_system_adv_advcasedetail extends controller_base_action {

	function __construct() {
		$this->objName = "advcasedetail";
		$this->objPath = "system_adv";
		parent::__construct ();
	 }


	 /**
	 * 获取所有数据返回json
	 */
	function c_listJson() {
		$this->service->asc=false;
		return parent::c_listJson();
	}


 }
?>
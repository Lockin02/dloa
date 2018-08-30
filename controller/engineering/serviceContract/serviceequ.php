<?php
/**
 * @author Administrator
 * @Date 2011年5月5日 10:02:07
 * @version 1.0
 * @description:服务合同配置清单控制层
 */
class controller_engineering_serviceContract_serviceequ extends controller_base_action {

	function __construct() {
		$this->objName = "serviceequ";
		$this->objPath = "engineering_serviceContract";
		parent::__construct ();
	 }

	/*
	 * 跳转到服务合同配置清单
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
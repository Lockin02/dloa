<?php
/**
 * @author Administrator
 * @Date 2011年5月5日 20:07:06
 * @version 1.0
 * @description:销售合同联系人信息表控制层
 */
class controller_projectmanagent_order_linkman extends controller_base_action {

	function __construct() {
		$this->objName = "linkman";
		$this->objPath = "projectmanagent_order";
		parent::__construct ();
	 }

	/*
	 * 跳转到销售合同联系人信息表
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
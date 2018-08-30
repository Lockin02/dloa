<?php
/**
 * @author Administrator
 * @Date 2011年12月12日 15:14:45
 * @version 1.0
 * @description:续借从表物料信息控制层
 */
class controller_projectmanagent_borrow_renewequ extends controller_base_action {

	function __construct() {
		$this->objName = "renewequ";
		$this->objPath = "projectmanagent_borrow";
		parent::__construct ();
	 }

	/*
	 * 跳转到续借从表物料信息
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
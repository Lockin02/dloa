<?php
/**
 * @author chenzb
 * @Date 2011年11月24日
 * @version 1.0
 * @description:控制层
 */
class controller_asset_checktask_checkitem extends controller_base_action {

	function __construct() {
		$this->objName = "checkitem";
		$this->objPath = "asset_checktask";
		parent::__construct ();
	 }

	/*
	 * 跳转到资产调拨清单
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

 }
?>
<?php
/**
 * @author chenzb
 * @Date 2011年11月24日
 * @version 1.0
 * @description:资产调拨清单控制层
 */
class controller_asset_daily_allocationitem extends controller_base_action {

	function __construct() {
		$this->objName = "allocationitem";
		$this->objPath = "asset_daily";
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
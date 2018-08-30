<?php
/**
 * @author chenzb
 * @Date 2011年11月21日
 * @version 1.0
 * @description:资产借用清单控制层
 */
class controller_asset_daily_rentitem extends controller_base_action {

	function __construct() {
		$this->objName = "rentitem";
		$this->objPath = "asset_daily";
		parent::__construct ();
	 }

	/*
	 * 跳转到资产借用清单
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

 }
?>
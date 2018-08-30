<?php
/**
*资产归还明细控制层
*@linzx
 */
class controller_asset_daily_returnitem extends controller_base_action {

	function __construct() {
		$this->objName = "returnitem";
		$this->objPath = "asset_daily";
		parent::__construct ();
	 }

	/*
	 * 跳转到资产归还清单
	 */
    function c_page() {
      $this->view('list');
    }

 }
?>
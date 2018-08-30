<?php
/**
*资产维保明细控制层
*@linzx
 */
class controller_asset_daily_keepitem extends controller_base_action {

	function __construct() {
		$this->objName = "keepitem";
		$this->objPath = "asset_daily";
		parent::__construct ();
	 }

	/*
	 * 跳转到资产维保清单
	 */
    function c_page() {
      $this->view('list');
    }

 }
?>
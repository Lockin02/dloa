<?php
/**
*�ʲ��黹��ϸ���Ʋ�
*@linzx
 */
class controller_asset_daily_returnitem extends controller_base_action {

	function __construct() {
		$this->objName = "returnitem";
		$this->objPath = "asset_daily";
		parent::__construct ();
	 }

	/*
	 * ��ת���ʲ��黹�嵥
	 */
    function c_page() {
      $this->view('list');
    }

 }
?>
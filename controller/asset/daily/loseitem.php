<?php
/**
*�ʲ���ʧ��ϸ���Ʋ�
*@linzx
 */
class controller_asset_daily_loseitem extends controller_base_action {

	function __construct() {
		$this->objName = "loseitem";
		$this->objPath = "asset_daily";
		parent::__construct ();
	 }

	/*
	 * ��ת���ʲ�ά���嵥
	 */
    function c_page() {
      $this->view('list');
    }

 }
?>
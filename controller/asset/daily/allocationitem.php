<?php
/**
 * @author chenzb
 * @Date 2011��11��24��
 * @version 1.0
 * @description:�ʲ������嵥���Ʋ�
 */
class controller_asset_daily_allocationitem extends controller_base_action {

	function __construct() {
		$this->objName = "allocationitem";
		$this->objPath = "asset_daily";
		parent::__construct ();
	 }

	/*
	 * ��ת���ʲ������嵥
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

 }
?>
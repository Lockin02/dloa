<?php
/**
 * @author chenzb
 * @Date 2011��11��24��
 * @version 1.0
 * @description:���Ʋ�
 */
class controller_asset_checktask_checkitem extends controller_base_action {

	function __construct() {
		$this->objName = "checkitem";
		$this->objPath = "asset_checktask";
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
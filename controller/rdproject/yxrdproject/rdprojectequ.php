<?php
/**
 * @author Administrator
 * @Date 2011��5��8�� 14:16:41
 * @version 1.0
 * @description:�з���Ʒ�嵥���Ʋ�
 */
class controller_rdproject_yxrdproject_rdprojectequ extends controller_base_action {

	function __construct() {
		$this->objName = "rdprojectequ";
		$this->objPath = "rdproject_yxrdproject";
		parent::__construct ();
	 }

	/*
	 * ��ת���з���Ʒ�嵥
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
<?php
/**
 * @author suxc
 * @version 1.0
 * @description:������ϵ�˿��Ʋ�
 */
class controller_projectmanagent_clues_linkman extends controller_base_action {

	function __construct() {
		$this->objName = "linkman";
		$this->objPath = "projectmanagent_clues";
		parent::__construct ();
	 }

	/*
	 * ��ת������������
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }



 }
?>
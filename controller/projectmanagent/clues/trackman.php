<?php
/**
 * @author Administrator
 * @Date 2011��3��5�� 10:20:18
 * @version 1.0
 * @description:���������˿��Ʋ�
 */
class controller_projectmanagent_clues_trackman extends controller_base_action {

	function __construct() {
		$this->objName = "trackman";
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
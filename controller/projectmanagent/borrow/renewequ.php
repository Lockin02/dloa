<?php
/**
 * @author Administrator
 * @Date 2011��12��12�� 15:14:45
 * @version 1.0
 * @description:����ӱ�������Ϣ���Ʋ�
 */
class controller_projectmanagent_borrow_renewequ extends controller_base_action {

	function __construct() {
		$this->objName = "renewequ";
		$this->objPath = "projectmanagent_borrow";
		parent::__construct ();
	 }

	/*
	 * ��ת������ӱ�������Ϣ
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
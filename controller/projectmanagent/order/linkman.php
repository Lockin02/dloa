<?php
/**
 * @author Administrator
 * @Date 2011��5��5�� 20:07:06
 * @version 1.0
 * @description:���ۺ�ͬ��ϵ����Ϣ����Ʋ�
 */
class controller_projectmanagent_order_linkman extends controller_base_action {

	function __construct() {
		$this->objName = "linkman";
		$this->objPath = "projectmanagent_order";
		parent::__construct ();
	 }

	/*
	 * ��ת�����ۺ�ͬ��ϵ����Ϣ��
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
 }
?>
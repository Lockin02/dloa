<?php
/**
 * @author Administrator
 * @Date 2011��3��4�� 14:58:38
 * @version 1.0
 * @description:�̻���ϵ����Ϣ����Ʋ�
 */
class controller_projectmanagent_chancelinker_chancelinker extends controller_base_action {

	function __construct() {
		$this->objName = "chancelinker";
		$this->objPath = "projectmanagent_chancelinker";
		parent::__construct ();
	 }

	/*
	 * ��ת���̻���ϵ����Ϣ��
	 */
    function c_page() {
      $this->display('list');
    }

    function c_toAddLiner(){
    	$this->display('add');
    }
 }
?>
<?php
/**
 * @author huangzf
 * @Date 2011��11��1�� 11:20:37
 * @version 1.0
 * @description:ϵͳ��־������ϸ���Ʋ� 
 */
class controller_syslog_setting_logsettingdetail extends controller_base_action {

	function __construct() {
		$this->objName = "logsettingdetail";
		$this->objPath = "syslog_setting";
		parent::__construct ();
	 }
    
	/*
	 * ��ת��ϵͳ��־������ϸ
	 */
    function c_page() {
      $this->view('list');
    }
 }
?>
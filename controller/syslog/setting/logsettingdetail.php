<?php
/**
 * @author huangzf
 * @Date 2011年11月1日 11:20:37
 * @version 1.0
 * @description:系统日志设置明细控制层 
 */
class controller_syslog_setting_logsettingdetail extends controller_base_action {

	function __construct() {
		$this->objName = "logsettingdetail";
		$this->objPath = "syslog_setting";
		parent::__construct ();
	 }
    
	/*
	 * 跳转到系统日志设置明细
	 */
    function c_page() {
      $this->view('list');
    }
 }
?>
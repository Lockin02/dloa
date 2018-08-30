<?php
/**
 * 定时任务
 * @author chengl
 *
 */
 class controller_common_timeTask extends controller_base_action {

	function __construct() {
		$this->objName = "timeTask";
		$this->objPath = "common";
		parent::__construct ();
	}

	/**
	 * 跳转到定时任务首页
	 */
	function c_toIndex(){
		$this->assign("hasTimeTask",$this->service->application('hasTimeTask'));
		$this->view ( 'index' );
	}

	/**
	 * 开启定时任务
	 */
	function c_startTimeTask(){
		$this->service->startTimeTask();
	}

	function c_setHasTimeTask(){
		$this->service->application('hasTimeTask',1); //设置 key=value
		//$value = application('key'); //获取 key的值
		echo 1;
	}

	function c_stopTimeTask(){
		$this->service->application('hasTimeTask',0); //设置 key=value
		echo 1;
	}


 }
?>

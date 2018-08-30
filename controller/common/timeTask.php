<?php
/**
 * ��ʱ����
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
	 * ��ת����ʱ������ҳ
	 */
	function c_toIndex(){
		$this->assign("hasTimeTask",$this->service->application('hasTimeTask'));
		$this->view ( 'index' );
	}

	/**
	 * ������ʱ����
	 */
	function c_startTimeTask(){
		$this->service->startTimeTask();
	}

	function c_setHasTimeTask(){
		$this->service->application('hasTimeTask',1); //���� key=value
		//$value = application('key'); //��ȡ key��ֵ
		echo 1;
	}

	function c_stopTimeTask(){
		$this->service->application('hasTimeTask',0); //���� key=value
		echo 1;
	}


 }
?>

<?php
/**
 * @author show
 * @Date 2014年08月01日 
 * @version 1.0
 * @description:设备借用申请锁定控制层 
 */
class controller_engineering_resources_lock extends controller_base_action {

	function __construct() {
		$this->objName = "lock";
		$this->objPath = "engineering_resources";
		parent::__construct ();
	}
	
	/**
	 * 设备申请锁定管理
	 */
	function c_page() {
		$this->view('list');
	}
	
	/**
	 * ajax方式解锁
	 */
	function c_ajaxUnlock() {
		try {
			$this->service->unlock_d ($_POST ['id']);
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}
	
	/**
	 * 检查当前用户是否存在设备申请锁定记录
	 */
	function c_checkLock(){
		$rs = $this->service->checkLock_d($_SESSION['USER_ID']);
		if($rs){
			echo 1;             //存在
		}else
			echo 0;            //不存在
	}
}
<?php
/**
 * @author show
 * @Date 2014��08��01�� 
 * @version 1.0
 * @description:�豸���������������Ʋ� 
 */
class controller_engineering_resources_lock extends controller_base_action {

	function __construct() {
		$this->objName = "lock";
		$this->objPath = "engineering_resources";
		parent::__construct ();
	}
	
	/**
	 * �豸������������
	 */
	function c_page() {
		$this->view('list');
	}
	
	/**
	 * ajax��ʽ����
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
	 * ��鵱ǰ�û��Ƿ�����豸����������¼
	 */
	function c_checkLock(){
		$rs = $this->service->checkLock_d($_SESSION['USER_ID']);
		if($rs){
			echo 1;             //����
		}else
			echo 0;            //������
	}
}
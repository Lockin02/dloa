<?php
/*
 * Created on 2010-7-11
 * �ɹ����뵥��Ʒ�嵥��Ϣ control
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

  class controller_purchase_apply_applyequiment extends controller_base_action {

 	function __construct() {
		$this->objName = "applyequipment";
		$this->objPath = "purchase_apply";
		parent :: __construct();
	}

	/**
	 * �ⲿ�ӿ�
	 * ͨ����Ʒ�嵥Id����δ�´�����
	 */
	function c_getAppProNotIssNum(){
		$purchAppProId = isset( $_GET['purchAppProId'] )?$_GET['purchAppProId']:exit;
		echo $this->service->getAppProNotIssNum_d($purchAppProId);
	}

  }
?>

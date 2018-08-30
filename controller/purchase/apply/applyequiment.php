<?php
/*
 * Created on 2010-7-11
 * 采购申请单产品清单信息 control
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
	 * 外部接口
	 * 通过产品清单Id返回未下达数量
	 */
	function c_getAppProNotIssNum(){
		$purchAppProId = isset( $_GET['purchAppProId'] )?$_GET['purchAppProId']:exit;
		echo $this->service->getAppProNotIssNum_d($purchAppProId);
	}

  }
?>

<?php


/**
 * ��Ӧ����Ӫ����Ʋ���
 * @author qian
 * @date 2011-03-11
 */
class controller_supplierManage_formal_bankinfo extends controller_base_action {
	/*
	 * ���캯��
	 */

	function __construct() {
		$this->objName = "bankinfo";
		$this->objPath = "supplierManage_formal";
		parent :: __construct();
	}
	function c_getBankInfo(){
		$suppId=isset($_POST['suppId'])?$_POST['suppId']:"";
		$rows=$this->service->getBankInfoBySuppId($suppId);
		if(is_array($rows)){
			echo util_jsonUtil::encode ( $rows['0'] );
		}else{
			echo "";
		}
	}

}
?>
<?php
/**
 * @author Administrator
 * @Date 2012-11-14 11:06:15
 * @version 1.0
 * @description:��Ŀ�豸������ϸ���Ʋ�
 */
class controller_engineering_resources_taskdetail extends controller_base_action {

	function __construct() {
		$this->objName = "taskdetail";
		$this->objPath = "engineering_resources";
		parent :: __construct();
	}
	
	/**
	 * �豸����Աȷ�Ϸ������������ӱ�
	 */
	function c_confirmTaskNumListJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->confirmTaskNumListJson_d ($_REQUEST['applyId']);
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}
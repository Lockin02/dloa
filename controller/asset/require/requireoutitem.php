<?php
/**
 * @author show
 * @Date 2014��09��01��
 * @version 1.0
 * @description:�ʲ�ת����������ϸ���Ʋ�
 */
class controller_asset_require_requireoutitem extends controller_base_action {

	function __construct() {
		$this->objName = "requireoutitem";
		$this->objPath = "asset_require";
		parent::__construct ();
	 }
    
	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listByRequireJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
	
	/**
	 * �ʲ����ʱ����ȡʵ�ʿ�������������
	 */
	function c_getNumAtInStock(){
		if (is_numeric($_POST['id']) && strlen($_POST['id']) < 32) {
			$rs = $this->service->find(array('id' => $_POST['id']),null,'number,executedNum');
			echo $rs['number'] - $rs['executedNum'];
		}
	}
 }
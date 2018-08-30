<?php
/**
 * @author show
 * @Date 2013��11��15�� 16:10:52
 * @version 1.0
 * @description:��Ŀ�豸������ϸ���Ʋ�
 */
class controller_engineering_resources_resourceapplydet extends controller_base_action {

	function __construct() {
		$this->objName = "resourceapplydet";
		$this->objPath = "engineering_resources";
		parent :: __construct();
	}

	/**
	 * ��ȡ�豸������´�������豸��ϸ
	 */
	function c_listJsonForTask() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		if($rows){
			foreach($rows as &$v){
				$v['needExeNum'] = $v['number'] - $v['exeNumber'];
				$v['thisExeNum'] = 0;
			}
		}
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ��ȡ�����豸���
	 */
	function c_getDetailAmount(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d('select_count');
		if($rows[0]['amount']){
			echo $rows[0]['amount'];
		}else{
			echo 0;
		}
		exit;
	}
}
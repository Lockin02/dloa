<?php
/**
 * @author Show
 * @Date 2013��7��1�� ������ 13:50:47
 * @version 1.0
 */
class controller_flights_balance_batchitem extends controller_base_action {
	function __construct() {
		$this->objName = "batchitem";
		$this->objPath = "flights_balance";
		parent :: __construct();
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		if($rows){
			foreach($rows as $key => $val){
				$rows[$key]['msgTypeName'] = $service->rtMsgType_d($val['msgType']);
			}
			//���ݼ��밲ȫ��
			$rows = $this->sconfig->md5Rows ( $rows );
		}
		echo util_jsonUtil::encode ( $rows );
	}
}
?>
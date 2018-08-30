<?php
/**
 * @author Show
 * @Date 2013年7月1日 星期五 13:50:47
 * @version 1.0
 */
class controller_flights_balance_batchitem extends controller_base_action {
	function __construct() {
		$this->objName = "batchitem";
		$this->objPath = "flights_balance";
		parent :: __construct();
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		if($rows){
			foreach($rows as $key => $val){
				$rows[$key]['msgTypeName'] = $service->rtMsgType_d($val['msgType']);
			}
			//数据加入安全码
			$rows = $this->sconfig->md5Rows ( $rows );
		}
		echo util_jsonUtil::encode ( $rows );
	}
}
?>
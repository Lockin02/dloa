<?php
/**
 * @author Show
 * @Date 2013年7月1日 星期五 14:18:47
 * @version 1.0
 */
class model_flights_balance_batchitem extends model_base {
	function __construct() {
		$this->tbl_name = "oa_flights_balance_item";
		$this->sql_map = "flights/balance/batchitemSql.php";
		parent :: __construct();
	}

	/**
	 * 根据类型返回结算中文
	 */
	function rtMsgType_d($thisVal){
		if($thisVal == '0'){
			return '正常';
		}else if($thisVal == "1"){
			return '改签';
		}else{
			return '退票';
		}
	}

	//获取结算明细
	function itemInfo_d($mainId) {
		$this->searchArr = array('mainId' => $mainId);
		return $this->list_d();
	}
}
?>
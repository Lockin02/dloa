<?php
/**
 * @author Show
 * @Date 2013��7��1�� ������ 14:18:47
 * @version 1.0
 */
class model_flights_balance_batchitem extends model_base {
	function __construct() {
		$this->tbl_name = "oa_flights_balance_item";
		$this->sql_map = "flights/balance/batchitemSql.php";
		parent :: __construct();
	}

	/**
	 * �������ͷ��ؽ�������
	 */
	function rtMsgType_d($thisVal){
		if($thisVal == '0'){
			return '����';
		}else if($thisVal == "1"){
			return '��ǩ';
		}else{
			return '��Ʊ';
		}
	}

	//��ȡ������ϸ
	function itemInfo_d($mainId) {
		$this->searchArr = array('mainId' => $mainId);
		return $this->list_d();
	}
}
?>
<?php
/**
 * @author liub
 * @Date 2012-04-07 14:03:01
 * @version 1.0
 * @description:������Ʒ�嵥 Model��
 */
 class model_projectmanagent_exchange_exchangeproduct  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_exchange_product";
		$this->sql_map = "projectmanagent/exchange/exchangeproductSql.php";
		parent::__construct ();
	}

	//����json�ַ���
	function resolve_d($id){
		$obj = $this->find(array( 'id' => $id ),null,'id,deploy');
		$goodsCacheDao = new model_goods_goods_goodscache();
		$newArr = $goodsCacheDao->changeToProduct_d($obj['deploy']);
		if(is_array($newArr)&&count($newArr)){
			return $newArr;
		}else{
			return 0;
		}
	}

	/**
	 * ���ݺ�ͬID ��ȡ�ӱ�����
	 */
	function getDetail_d($exchangeId) {
		$this->searchArr ['exchangeId'] = $exchangeId;
		$this->searchArr ['isDel'] = 0;
		$this->searchArr ['isTemp'] = 0;
		$this->asc = false;
		return $this->list_d ();
	}


	/**
	 * ���ݺ�ͬID ��ȡ�ӱ�����
	 */
	function getDetailWithTemp_d($exchangeId) {
		$this->searchArr ['exchangeId'] = $exchangeId;
//		$this->searchArr ['isDel'] = 0;
		$this->searchArr ['isTemp'] = 0;
		$this->asc = false;
		return $this->list_d ();
	}


}
?>
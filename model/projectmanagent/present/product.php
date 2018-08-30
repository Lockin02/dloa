<?php
/**
 * @author liub
 * @Date 2012��3��8�� 14:13:30
 * @version 1.0
 * @description:��Ʒ�嵥 Model��
 */
 class model_projectmanagent_present_product  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_present_product";
		$this->sql_map = "projectmanagent/present/productSql.php";
		parent::__construct ();
	}

	//����json�ַ���
	function resolve_d($id){
		$obj = $this->find(array( 'id' => $id ),null,'id,deploy');

//		print_r($obj);
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
	function getDetail_d($presentId) {
		$this->searchArr ['presentId'] = $presentId;
		$this->searchArr ['isDel'] = 0;
		$this->searchArr ['isTemp'] = 0;
		$this->asc = false;
		return $this->list_d ();
	}


	/**
	 * ���ݺ�ͬID ��ȡ�ӱ�����
	 */
	function getDetailWithTemp_d($presentId) {
		$this->searchArr ['presentId'] = $presentId;
//		$this->searchArr ['isDel'] = 0;
//		$this->searchArr ['isTemp'] = 0;
		$this->asc = false;
		return $this->list_d ();
	}

}
?>
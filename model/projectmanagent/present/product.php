<?php
/**
 * @author liub
 * @Date 2012年3月8日 14:13:30
 * @version 1.0
 * @description:产品清单 Model层
 */
 class model_projectmanagent_present_product  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_present_product";
		$this->sql_map = "projectmanagent/present/productSql.php";
		parent::__construct ();
	}

	//解析json字符串
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
	 * 根据合同ID 获取从表数据
	 */
	function getDetail_d($presentId) {
		$this->searchArr ['presentId'] = $presentId;
		$this->searchArr ['isDel'] = 0;
		$this->searchArr ['isTemp'] = 0;
		$this->asc = false;
		return $this->list_d ();
	}


	/**
	 * 根据合同ID 获取从表数据
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
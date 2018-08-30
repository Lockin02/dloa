<?php
/**
 * @author liub
 * @Date 2012年3月8日 14:13:30
 * @version 1.0
 * @description:产品清单 Model层
 */
 class model_projectmanagent_borrow_product  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_borrow_product";
		$this->sql_map = "projectmanagent/borrow/productSql.php";
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
	function getDetail_d($borrowId) {
		$this->searchArr ['borrowId'] = $borrowId;
		$this->searchArr ['isDel'] = 0;
		$this->searchArr ['isTemp'] = 0;
		$this->asc = false;
		return $this->list_d ();
	}

	/**
	 * 根据合同ID 获取从表数据
	 */
	function getDetailWithTemp_d($borrowId) {
		$this->searchArr ['borrowId'] = $borrowId;
		// $this->searchArr ['isTemp'] = 0;
		$this->asc = false;
		$rows =  $this->list_d ();
		return $rows;
	}

	/**
	 * 获取产品信息
	 * @param $data
	 * @return mixed
	 */
	function dealProduct_d($data) {
		if ($data) {
			$productIdArr = array();
			foreach ($data as $k => $v) {
				if (!in_array($v['conProductId'], $productIdArr)) {
					$productIdArr[] = $v['conProductId'];
				}
			}
	
			// 初始化产品查询
			$goodsDao = new model_goods_goods_goodsbaseinfo();
			$goodsInfo = $goodsDao->getGoodsHashInfo_d($productIdArr);
	
			foreach ($data as $k => $v) {
				$data[$k]['proExeDeptName'] = $goodsInfo[$v['conProductId']]['auditDeptName'];
				$data[$k]['proExeDeptId'] = $goodsInfo[$v['conProductId']]['auditDeptCode'];
				$data[$k]['newExeDeptCode'] = $goodsInfo[$v['conProductId']]['exeDeptCode'];
				$data[$k]['newExeDeptName'] = $goodsInfo[$v['conProductId']]['exeDeptName'];
			}
		}
		return $data;
	}
}
?>
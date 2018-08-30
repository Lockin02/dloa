<?php
/**
 * @author liub
 * @Date 2012��3��8�� 14:13:30
 * @version 1.0
 * @description:��Ʒ�嵥 Model��
 */
 class model_projectmanagent_borrow_product  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_borrow_product";
		$this->sql_map = "projectmanagent/borrow/productSql.php";
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
	function getDetail_d($borrowId) {
		$this->searchArr ['borrowId'] = $borrowId;
		$this->searchArr ['isDel'] = 0;
		$this->searchArr ['isTemp'] = 0;
		$this->asc = false;
		return $this->list_d ();
	}

	/**
	 * ���ݺ�ͬID ��ȡ�ӱ�����
	 */
	function getDetailWithTemp_d($borrowId) {
		$this->searchArr ['borrowId'] = $borrowId;
		// $this->searchArr ['isTemp'] = 0;
		$this->asc = false;
		$rows =  $this->list_d ();
		return $rows;
	}

	/**
	 * ��ȡ��Ʒ��Ϣ
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
	
			// ��ʼ����Ʒ��ѯ
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
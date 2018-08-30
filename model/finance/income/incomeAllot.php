<?php
/**
 * 到款分配model层类
 */
class model_finance_income_incomeAllot extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_income_allot";
		$this->sql_map = "finance/income/incomeAllotSql.php";
		parent :: __construct();
		parent :: setObjAss();
	}

	/**
	 * 到款类型过滤
	 */
	function rtPostVla($thisVal){
		$val = null;
		switch ($thisVal){
			case 'KPRK-01' :
			case 'KPRK-02' : $val = 'KPRK-01,KPRK-02' ;break;
			case 'KPRK-03' :
			case 'KPRK-04' : $val = 'KPRK-03,KPRK-04' ;break;
			case 'KPRK-05' :
			case 'KPRK-06' : $val = 'KPRK-05,KPRK-06' ;break;
			case 'KPRK-07' :
			case 'KPRK-08' : $val = 'KPRK-07,KPRK-08' ;break;
			case 'KPRK-09' : $val = 'KPRK-09' ;break;
			case 'KPRK-10' : $val = 'KPRK-10' ;break;
			case 'KPRK-11' : $val = 'KPRK-11' ;break;
			case 'KPRK-12' : $val = 'KPRK-12' ;break;
			default : $val = $thisVal;break;
		}
		return $val;
	}

	/**
	 * 到款类型返回对应对象类
	 */
	function rtTypeClass($thisVal){
		$val = null;
		switch ($thisVal){
			case 'KPRK-01' :
			case 'KPRK-02' : $val = 'projectmanagent_order_order' ;break;
			case 'KPRK-03' :
			case 'KPRK-04' : $val = 'engineering_serviceContract_serviceContract' ;break;
			case 'KPRK-05' :
			case 'KPRK-06' : $val = 'contract_rental_rentalcontract' ;break;
			case 'KPRK-07' :
			case 'KPRK-08' : $val = 'rdproject_yxrdproject_rdproject' ;break;
			case 'KPRK-09' : $val = 'contract_other_other' ;break;
			case 'KPRK-10' : $val = 'service_accessorder_accessorder' ;break;
			case 'KPRK-11' : $val = 'service_repair_repairapply' ;break;
			case 'KPRK-12' : $val = 'contract_contract_contract' ;break;
			default : $val = $thisVal;break;
		}
		return $val;
	}

	//需要处理的类型
	private $needDealArr = array('KPRK-12');

	/***************************************************************************************************
	 * ------------------------------以下为公用接口方法,可以为其他模块所调用------------------------------
	 *************************************************************************************************/

	/*
	 * 根据到款获取到款分配
	 */
	function getAllotsByIncomeId($incomeId) {
		$this->searchArr = array (
			'incomeId' => $incomeId
		);
		$this->asc = false;
		return $this->list_d();
	}

	/*
	 * 根据到款删除到款分配
	 */
	function deleteAllotsByIncomeId($incomeId) {
		$condition = array (
			"incomeId" => $incomeId
		);
		$this->delete($condition);
	}

	/**
	 * 根据合同编号获取到款信息
	 */
	function getIncomeAllotByContract($contractNumber) {
		$this->searchArr = array (
			"contractNumber" => $contractNumber
		);
		return parent::list_d('select_allotinobj');
	}

	/**
	 * 根据源单Id和源单类型获取到款信息
	 */
	function getIncomes_d($objId,$objType = 'KPRK-01'){
		$this->searchArr = array(
			'objId' => $objId,
			'objType' => $objType
		);
		$this->sort = 'i.incomeDate asc,i.createTime';
		$this->asc = false;
		return parent::list_d('select_allotinobj');
	}

	/*********************** 新合同部分 ********************/
	/**
	 * 业务处理 - 传入值为数组
	 */
	function businessDeal_d($object,$oldObject = null){
		//对象缓存数组，避免同一对象重复初始化
		$objectArr = array();
		try{
			foreach($object as $val){
				if(in_array($val['objType'],$this->needDealArr)){
					$thisClass = $this->rtTypeClass($val['objType']);
					$thisClass = 'model_'.$thisClass;

					if(!isset($objectArr[$thisClass])){
						$objectArr[$thisClass] = new $thisClass();
					}

					$innerObjDao = $objectArr[$thisClass];

					//获取已到款金额
					$incomeMoney = $this->getIncomeMoney_d($val);

					//更新已到款金额
					$innerObjDao->update(array('id' => $val['objId']),array('incomeMoney' => $incomeMoney ));
                    // 调用合同自动关闭方法
                    $innerObjDao->updateContractClose($val['objId']);
				}
			}

			//原数据统计更新
			if(!empty($oldObject)){
				foreach($oldObject as $val){
					if(in_array($val['objType'],$this->needDealArr)){
						$thisClass = $this->rtTypeClass($val['objType']);
						$thisClass = 'model_'.$thisClass;

						if(!isset($objectArr[$thisClass])){
							$objectArr[$thisClass] = new $thisClass();
						}

						$innerObjDao = $objectArr[$thisClass];

						//获取已到款金额
						$incomeMoney = $this->getIncomeMoney_d($val);

						//更新已到款金额
						$innerObjDao->update(array('id' => $val['objId']),array('incomeMoney' => $incomeMoney ));
                        // 调用合同自动关闭方法
                        $innerObjDao->updateContractClose($val['objId']);
					}
				}
			}
			return true;
		}catch(exception $e){
			throw $e;
		}
	}

	/**
	 * 获取业务对象已到款金额
	 */
	function getIncomeMoney_d($object){
		$this->searchArr = array( 'objId' => $object['objId'] , 'objType' => $object['objType']);
		$this->groupBy = 'c.objId,c.objType';
		$this->sort = 'c.objId';
		$rs = $this->list_d('select_income');
		if(is_array($rs)){
			return $rs[0]['incomeMoney'];
		}else{
			return 0;
		}
	}
}
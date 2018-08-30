<?php
/**
 * �������model����
 */
class model_finance_income_incomeAllot extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_income_allot";
		$this->sql_map = "finance/income/incomeAllotSql.php";
		parent :: __construct();
		parent :: setObjAss();
	}

	/**
	 * �������͹���
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
	 * �������ͷ��ض�Ӧ������
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

	//��Ҫ���������
	private $needDealArr = array('KPRK-12');

	/***************************************************************************************************
	 * ------------------------------����Ϊ���ýӿڷ���,����Ϊ����ģ��������------------------------------
	 *************************************************************************************************/

	/*
	 * ���ݵ����ȡ�������
	 */
	function getAllotsByIncomeId($incomeId) {
		$this->searchArr = array (
			'incomeId' => $incomeId
		);
		$this->asc = false;
		return $this->list_d();
	}

	/*
	 * ���ݵ���ɾ���������
	 */
	function deleteAllotsByIncomeId($incomeId) {
		$condition = array (
			"incomeId" => $incomeId
		);
		$this->delete($condition);
	}

	/**
	 * ���ݺ�ͬ��Ż�ȡ������Ϣ
	 */
	function getIncomeAllotByContract($contractNumber) {
		$this->searchArr = array (
			"contractNumber" => $contractNumber
		);
		return parent::list_d('select_allotinobj');
	}

	/**
	 * ����Դ��Id��Դ�����ͻ�ȡ������Ϣ
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

	/*********************** �º�ͬ���� ********************/
	/**
	 * ҵ���� - ����ֵΪ����
	 */
	function businessDeal_d($object,$oldObject = null){
		//���󻺴����飬����ͬһ�����ظ���ʼ��
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

					//��ȡ�ѵ�����
					$incomeMoney = $this->getIncomeMoney_d($val);

					//�����ѵ�����
					$innerObjDao->update(array('id' => $val['objId']),array('incomeMoney' => $incomeMoney ));
                    // ���ú�ͬ�Զ��رշ���
                    $innerObjDao->updateContractClose($val['objId']);
				}
			}

			//ԭ����ͳ�Ƹ���
			if(!empty($oldObject)){
				foreach($oldObject as $val){
					if(in_array($val['objType'],$this->needDealArr)){
						$thisClass = $this->rtTypeClass($val['objType']);
						$thisClass = 'model_'.$thisClass;

						if(!isset($objectArr[$thisClass])){
							$objectArr[$thisClass] = new $thisClass();
						}

						$innerObjDao = $objectArr[$thisClass];

						//��ȡ�ѵ�����
						$incomeMoney = $this->getIncomeMoney_d($val);

						//�����ѵ�����
						$innerObjDao->update(array('id' => $val['objId']),array('incomeMoney' => $incomeMoney ));
                        // ���ú�ͬ�Զ��رշ���
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
	 * ��ȡҵ������ѵ�����
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
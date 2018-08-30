<?php
/*
 * Created on 2011-3-13
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

include_once( WEB_TOR . 'model/contract/stamp/istamp.php');
/**
 * 销售开票申请策略
 */
class model_contract_stamp_strategy_purcontract extends model_base implements istamp{

	//对应业务类
	private $thisClass = 'model_purchase_contract_purchasecontract';

	/**
	 * 是否返回
	 */
	function rtYesOrNo($val){
		if($val == 1){
			return '是';
		}else{
			return '否';
		}
	}

	/****************************业务接口************************/

	/**
	 * 确认盖章页面内容初始化
	 */
	function initStamp_i($obj){
		$innerObjDao = new $this->thisClass();
		$innerObj = $innerObjDao->get_d($obj['contractId']);
		$equs = $innerObjDao->getEquipments_d ($obj['contractId']);

		//从表信息
		$innerObj['list'] =  $innerObjDao->addContractEquList_s ( $equs );

		//附件信息
		$innerObj['file'] = $innerObjDao->getFilesByObjId ( $obj ['contractId'], false,$innerObjDao->tbl_name );

		//数据字典处理
		$datadictDao = new model_system_datadict_datadict();

		$innerObj['bType'] = $datadictDao->getDataNameByCode($innerObj['billingType']);
		$innerObj['pType'] = $datadictDao->getDataNameByCode($innerObj['paymentType']);
		$innerObj['paymentCondition'] = $datadictDao->getDataNameByCode($innerObj['paymentCondition']);
		$innerObj['suppBank'] = $datadictDao->getDataNameByCode($innerObj['suppBank']);
		$innerObj['signStatus'] = $innerObjDao->signStatus_d($innerObj['signStatus']);

		$innerObj['isStamp'] = $this->rtYesOrNo($innerObj['isStamp']);
		$innerObj['isNeedStamp'] = $this->rtYesOrNo($innerObj['isNeedStamp']);

		return $innerObj;
	}

	/**
	 * 获取业务对象信息
	 */
	function getObjInfo_i($obj){
		$innerObjDao = new $this->thisClass();
		$innerObj = $innerObjDao->get_d($obj['contractId']);

		return $innerObj;
	}

	/**
	 * 更新业务信息
	 */
	function updateContract_i($obj){
		try{
			$innerObjDao = new $this->thisClass();

			$innerObj = array(
				'id' => $obj['contractId'],
				'stampType' => $obj['stampType'],
				'isStamp' => 1
			);
			return $innerObjDao->edit_d($innerObj);
		}catch(exception $e){
			throw $e;
		}
	}


    /**
     * 更新业务信息
     */
    function editStampType_i($obj){
        try{
            $innerObjDao = new $this->thisClass();

            $innerObj = array(
                'id' => $obj['contractId'],
                'stampType' => $obj['stampType']
            );
            return $innerObjDao->edit_d($innerObj);
        }catch(exception $e){
            throw $e;
        }
    }

    /**
     * 更新盖章类型和盖章状态
     */
    function editStampTypeAndStatus_i($obj){
		try{
			$innerObjDao = new $this->thisClass();

			$innerObj = array(
				'id' => $obj['contractId'],
				'stampType' => $obj['stampType'],
				'isStamp' => 1
			);
			return $innerObjDao->edit_d($innerObj);
		}catch(exception $e){
			throw $e;
		}
    }

    /**
     * 重置单据盖章信息
     */
    function resetStampType_i($obj){
        try{
            $innerObjDao = new $this->thisClass();

            $innerObj = array(
                'id' => $obj['contractId'],
                'stampType' => '',
                'isStamp' => 0,
                'isNeedStamp' => 0
            );
            return $innerObjDao->edit_d($innerObj);
        }catch(exception $e){
            throw $e;
        }
    }
}

?>

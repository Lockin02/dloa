<?php
/*
 * Created on 2011-3-13
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

include_once( WEB_TOR . 'model/contract/stamp/istamp.php');
/**
 * ���ۿ�Ʊ�������
 */
class model_contract_stamp_strategy_purcontract extends model_base implements istamp{

	//��Ӧҵ����
	private $thisClass = 'model_purchase_contract_purchasecontract';

	/**
	 * �Ƿ񷵻�
	 */
	function rtYesOrNo($val){
		if($val == 1){
			return '��';
		}else{
			return '��';
		}
	}

	/****************************ҵ��ӿ�************************/

	/**
	 * ȷ�ϸ���ҳ�����ݳ�ʼ��
	 */
	function initStamp_i($obj){
		$innerObjDao = new $this->thisClass();
		$innerObj = $innerObjDao->get_d($obj['contractId']);
		$equs = $innerObjDao->getEquipments_d ($obj['contractId']);

		//�ӱ���Ϣ
		$innerObj['list'] =  $innerObjDao->addContractEquList_s ( $equs );

		//������Ϣ
		$innerObj['file'] = $innerObjDao->getFilesByObjId ( $obj ['contractId'], false,$innerObjDao->tbl_name );

		//�����ֵ䴦��
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
	 * ��ȡҵ�������Ϣ
	 */
	function getObjInfo_i($obj){
		$innerObjDao = new $this->thisClass();
		$innerObj = $innerObjDao->get_d($obj['contractId']);

		return $innerObj;
	}

	/**
	 * ����ҵ����Ϣ
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
     * ����ҵ����Ϣ
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
     * ���¸������ͺ͸���״̬
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
     * ���õ��ݸ�����Ϣ
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

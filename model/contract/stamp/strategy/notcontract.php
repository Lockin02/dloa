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
class model_contract_stamp_strategy_notcontract extends model_base implements istamp{

	//��Ӧҵ����
	private $thisClass = 'model_contract_stamp_stampapply';

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
        $applyDao = new model_contract_stamp_stampapply();
        $applyObj = $applyDao->get_d($obj['applyId']);
        $applyObj['file'] = $applyDao->getFilesByObjId ( $obj ['applyId'], false,$applyDao->tbl_name );
        $applyObj['status'] =  $applyDao->rtStampType_d($applyObj['status']);

        //�����ֵ䲿��
        $datadictDao = new model_system_datadict_datadict();
        $applyObj['contractType'] = $datadictDao->getDataNameByCode($applyObj['contractType']);

        return $applyObj;
	}

	/**
	 * ��ȡҵ�������Ϣ
	 */
	function getObjInfo_i($obj){
		$applyDao = new model_contract_stamp_stampapply();
		$applyObj = $applyDao->get_d($obj['applyId']);

		return $applyObj;
	}

	/**
	 * ����ҵ����Ϣ
	 */
	function updateContract_i($obj){
		try{
			$innerObjDao = new $this->thisClass();

			$conditionArr = array(
				'id' => $obj['applyId']
			);
			$innerObj = array(
//				'stampType' => $obj['stampType'],
				'status' => 1
			);
			return $innerObjDao->update($conditionArr,$innerObj);
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

            $conditionArr = array(
				'id' => $obj['applyId']
			);
			$innerObj = array(
				'stampType' => $obj['stampType']
			);
			return $innerObjDao->update($conditionArr,$innerObj);
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

            $conditionArr = array(
				'id' => $obj['applyId']
			);
			$innerObj = array(
				'stampType' => $obj['stampType'],
				'status' => 1
			);
			return $innerObjDao->update($conditionArr,$innerObj);
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

            $conditionArr = array(
				'id' => $obj['applyId']
			);
            $innerObj = array(
                'stampType' => '',
                'status' => 0
            );
            return $innerObjDao->update($conditionArr,$innerObj);
        }catch(exception $e){
            throw $e;
        }
    }
}

?>

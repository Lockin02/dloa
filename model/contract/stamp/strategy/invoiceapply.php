<?php

include_once( WEB_TOR . 'model/contract/stamp/istamp.php');
class model_contract_stamp_strategy_invoiceapply extends model_base implements istamp{
	
	//��Ӧҵ����
	private $thisClass = 'model_finance_invoiceapply_invoiceapply';
	
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
		$innerObj = $innerObjDao->get_d($obj['contractId'],'view');
		$innerObj['file'] = $innerObjDao->getFilesByObjId ( $obj ['contractId'], false,$innerObjDao->tbl_name );
	
		$datadictDao = new model_system_datadict_datadict();
		$innerObj['fundTypeCN'] = $datadictDao->getDataNameByCode($innerObj['fundType']);
		//		$innerObj['stampType'] = $datadictDao->getDataNameByCode($innerObj['stampType']);
	
		$innerObj['isStamp'] = $this->rtYesOrNo($innerObj['isStamp']);
		$innerObj['isNeedStamp'] = $this->rtYesOrNo($innerObj['isNeedStamp']);
		
		$innerObj['createDate'] = date('Y-m-d',strtotime($innerObj['createTime']));
	
		$innerObj['invoiceTypeCN'] = $datadictDao->getDataNameByCode ( $innerObj['invoiceType']);
		$innerObj['customerTypeCN'] = $datadictDao->getDataNameByCode( $innerObj['customerType']);
		$innerObj['objTypeCN'] = $datadictDao->getDataNameByCode( $innerObj['objTypeCN']);
		
		//ǩ��״̬
		//$innerObj['signedStatusCN'] = $innerObjDao->rtIsSign_d($obj ['signedStatus']);
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
					//				'stampType' => $obj['stampType'],
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

<?php

include_once( WEB_TOR . 'model/contract/stamp/istamp.php');
class model_contract_stamp_strategy_invoiceapply extends model_base implements istamp{
	
	//对应业务类
	private $thisClass = 'model_finance_invoiceapply_invoiceapply';
	
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
		
		//签收状态
		//$innerObj['signedStatusCN'] = $innerObjDao->rtIsSign_d($obj ['signedStatus']);
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
					//				'stampType' => $obj['stampType'],
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

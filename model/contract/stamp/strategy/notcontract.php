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
class model_contract_stamp_strategy_notcontract extends model_base implements istamp{

	//对应业务类
	private $thisClass = 'model_contract_stamp_stampapply';

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
        $applyDao = new model_contract_stamp_stampapply();
        $applyObj = $applyDao->get_d($obj['applyId']);
        $applyObj['file'] = $applyDao->getFilesByObjId ( $obj ['applyId'], false,$applyDao->tbl_name );
        $applyObj['status'] =  $applyDao->rtStampType_d($applyObj['status']);

        //数据字典部分
        $datadictDao = new model_system_datadict_datadict();
        $applyObj['contractType'] = $datadictDao->getDataNameByCode($applyObj['contractType']);

        return $applyObj;
	}

	/**
	 * 获取业务对象信息
	 */
	function getObjInfo_i($obj){
		$applyDao = new model_contract_stamp_stampapply();
		$applyObj = $applyDao->get_d($obj['applyId']);

		return $applyObj;
	}

	/**
	 * 更新业务信息
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
     * 更新业务信息
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
     * 更新盖章类型和盖章状态
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
     * 重置单据盖章信息
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

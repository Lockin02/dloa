<?php
/*
 * Created on 2012-5-18
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

include_once( WEB_TOR . 'model/engineering/project/iesmproject.php');

/**
 * 试用项目策略
 */
class model_engineering_project_strategy_sService extends model_base implements iesmproject{

	//对应业务类
	private $thisClass = 'model_engineering_serviceContract_serviceContract';

	/**
	 * 获取业务对象信息
	 */
	function getObjInfo_i($obj){
		$innerObjDao = new $this->thisClass();
		$innerObj = $innerObjDao->get_d($obj['contractId']);

		$innerObj['contractCode'] = $innerObj['projectCode'];
		unset($innerObj['projectCode']);

		$innerObj['budgetAll'] = $innerObj['budgetMoney'];
		unset($innerObj['budgetMoney']);

		$innerObj['proName'] = $innerObj['province'];
		unset($innerObj['province']);

		return $innerObj;
	}

	/**
	 * 新增业务处理
	 */
	function businessAdd_i($obj,$mainDao){
		//更新源单状态为 执行中
		$innerObjDao = new $this->thisClass();

		try{
			$innerObjDao->start_d();

			$thisWorkRate = $mainDao->getAllWorkRateByType_d($obj['contractId'],$obj['contractType']);

			if($thisWorkRate == 100){
				$innerObjDao->update(array('id' => $obj['contractId']),array('dealStatus'=>1));
			}

            $orderDao = new model_projectmanagent_order_order();
            //获取外部合同类型
            $outContractType = $mainDao->rtOutCountType($obj['contractType']);
            //构建数组
            $contractArr =  array('id'=>$obj['contractId'],'tablename' =>$outContractType);
//            //调用合同业务处理
            $orderDao->updateProjectProcess($contractArr);

			$innerObjDao->commit_d();
			return true;
		}catch(exception $e){
			$innerObjDao->rollBack();
			throw $e;
		}
	}

	/**
	 * 确认业务处理
	 */
	function businessConfirm_i($obj,$mainDao = null){
		return true;
//		if(empty($mainDao)){
//			return true;
//		}else{
//			$innerObjDao = new model_projectmanagent_order_order();
//
//			try{
//				$innerObjDao->start_d();
//				//获取外部合同类型
//				$outContractType = $mainDao->rtOutCountType($obj['contractType']);
//				//构建数组
//				$contractArr =  array('id'=>$obj['contractId'],'tablename' =>$outContractType);
//				//调用合同业务处理
//				$innerObjDao->updateProjectProcess($contractArr);
//
//				$innerObjDao->commit_d();
//				return true;
//			}catch(exception $e){
//				$innerObjDao->rollBack();
//				throw $e;
//			}
//		}
	}

	/**
	 * 删除业务处理
	 */
	function businessDelete_i($obj){
		//更新源单状态为 执行中
		$innerObjDao = new $this->thisClass();

		try{
			$innerObjDao->start_d();

			$innerObjDao->update(array('id' => $obj['contractId']),array('dealStatus'=>0));

			$innerObjDao->commit_d();
			return true;
		}catch(exception $e){
			$innerObjDao->rollBack();
			throw $e;
		}
	}

	/**
	 * 关闭
	 */
	function businessClose_i($obj){

		return true;
//		//更新源单状态为 执行中
//		$innerObjDao = new $this->thisClass();
//
//		try{
//			$innerObjDao->start_d();
//
//			$innerObjDao->update(array('id' => $obj['contractId']),array('status'=>'4'));
//
//			$innerObjDao->commit_d();
//			return true;
//		}catch(exception $e){
//			$innerObjDao->rollBack();
//			throw $e;
//		}
	}

	/**
	 * 关联项目业务操作
	 */
	function businessConnect_i($obj,$mainDao){
		//更新源单状态为 执行中
		$innerObjDao = new $this->thisClass();

		try{
			$innerObjDao->start_d();

			$thisWorkRate = $mainDao->getAllWorkRateByType_d($obj['contractId'],$obj['contractType']);

			if($thisWorkRate == 100){
				$innerObjDao->update(array('id' => $obj['contractId']),array('dealStatus'=>1));
			}

			$innerObjDao->commit_d();
			return true;
		}catch(exception $e){
			$innerObjDao->rollBack();
			throw $e;
		}
	}

	/**
	 * 获取用于项目章程的信息
	 */
	function businessForCharter_i($obj,$mainDao){
		//更新源单状态为 执行中
		$contractDao = new $this->thisClass();
    	$contractArr = $contractDao->get_d($obj['contractId'],'none');
		$contractArr['tablename'] = $obj['contractType'];
		$contractArr['contractType'] = 'GCXMYD-02';
		$contractArr['contractMoney'] = $contractArr['orderMoney']*1 > 0 ? $contractArr['orderMoney'] : $contractArr['orderTempMoney'];

		return $contractArr;
	}
}
?>

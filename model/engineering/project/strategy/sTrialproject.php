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
class model_engineering_project_strategy_sTrialproject extends model_base implements iesmproject{

	//对应业务类
	private $thisClass = 'model_projectmanagent_trialproject_trialproject';

	/**
	 * 获取业务对象信息
	 */
	function getObjInfo_i($obj){
		$innerObj = $this->getRawInfo_i($obj['contractId']);

		$innerObj['contractCode'] = $innerObj['projectCode'];

		$innerObj['budgetAll'] = $innerObj['budgetMoney'];
		unset($innerObj['budgetMoney']);

		$innerObj['planBeginDate'] = $innerObj['beginDate'];
		unset($innerObj['beginDate']);

		$innerObj['planEndDate'] = $innerObj['closeDate'];
		unset($innerObj['closeDate']);

		$innerObj['expectedDuration'] = (strtotime($innerObj['planEndDate']) - strtotime($innerObj['planBeginDate']))/86400 + 1;

		//根据源单省份自动查询大区经理
		$managerDao = new model_engineering_officeinfo_manager();
		$provinceObj = $managerDao->getManager_d($innerObj['province']);
		$innerObj['areaManagerId'] = $provinceObj['areaManagerId'];
		$innerObj['areaManager'] = $provinceObj['areaManager'];

		return $innerObj;
	}

	/**
	 * 新增业务处理
	 */
	function businessAdd_i($obj){
		//更新源单状态为 执行中
		$innerObjDao = new $this->thisClass();

		try{
			$innerObjDao->update(array('id' => $obj['contractId']),array('status'=>'3'));

			return true;
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
	 * 确认业务处理
	 */
	function businessConfirm_i($obj){
		//更新源单状态为 执行中
		$innerObjDao = new $this->thisClass();

		try{
			$triProject = array('projectProcess'=>$obj['projectProcess']);

			// 根据项目状态更新PK项目状态
			if ($obj['status'] == "GCXMZT00") {
				$triProject['status'] = '3';
			} else if (in_array($obj['status'], array('GCXMZT00', 'GCXMZT03', 'GCXMZT04'))) {
				$triProject['status'] = '4';
			}

			$innerObjDao->update(array('id' => $obj['contractId']), $triProject);

			return true;
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
	 * 删除业务处理
	 */
	function businessDelete_i($obj){
		//更新源单状态为 执行中
		$innerObjDao = new $this->thisClass();

		try{
			$innerObjDao->update(array('id' => $obj['contractId']),array('status'=>'2'));

			return true;
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
	 * 删除业务处理
	 */
	function businessClose_i($obj){
		//更新源单状态为 执行中
		$innerObjDao = new $this->thisClass();

		try{
			$innerObjDao->update(array('id' => $obj['contractId']),array('status'=>'4'));

			return true;
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
	 * 关联项目业务操作
	 */
	function businessConnect_i($obj,$mainDao){
		return true;
	}

	/**
	 * 获取用于项目章程的信息
	 */
	function businessForCharter_i($obj,$mainDao){
		return true;
	}
	
	/**
	 * 获取业务对象原始信息
	 */
	function getRawInfo_i($id){
		$innerObjDao = new $this->thisClass();
		return $innerObjDao->get_d($id);
	}
}
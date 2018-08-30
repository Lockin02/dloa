<?php

/**
 * @author Show
 * @Date 2012年8月31日 星期五 14:53:01
 * @version 1.0
 * @description:员工试用培训计划 Model层
 */
class model_hr_trialplan_trialplan extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_trialplan";
		$this->sql_map = "hr/trialplan/trialplanSql.php";
		parent :: __construct();
	}

	/**************** 增删改查 ****************/
	/**
	 * 新增
	 */
	function add_d($object){
		//获取从表信息
		$trialpalndetail = $object['trialpalndetail'];
		unset($object['trialpalndetail']);

		try{
			$this->start_d();
			//新增
			$newId = parent::add_d($object,true);

			//从表部分
			$trialpalndetailDao = new model_hr_trialplan_trialplandetail();
			$rtArr = $trialpalndetailDao->batchAdd_d($trialpalndetail,array('planId' => $newId,'memberName' => $object['memberName'],'memberId' => $object['memberId']));

			//人事信息更新
			$personnelDao = new model_hr_personnel_personnel();
			$personnelArr = array(
				'trialPlanId' => $newId,
				'trialPlan' => $object['planName'],
				'trialTaskId' => $rtArr['id'],
				'trialTask' => $rtArr['taskName'],
				'baseScore' => $object['baseScore'],
				'trialPlanProcess' => 0
			);
			$personnelDao->updatePersonnel_d($object['memberId'],$personnelArr);


			$this->commit_d();
			return $newId;
		}catch(exception $e){
			echo $e;
			$this->rollBack();
			return false;
		}
	}
}
?>
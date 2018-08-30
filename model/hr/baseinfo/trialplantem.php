<?php
/**
 * @author Show
 * @Date 2012年8月30日 星期四 14:37:54
 * @version 1.0
 * @description:员工试用培训计划模板 Model层
 */
 class model_hr_baseinfo_trialplantem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_baseinfo_trialplantem";
		$this->sql_map = "hr/baseinfo/trialplantemSql.php";
		parent::__construct ();
	}

	/*********************** 增删改查 **************/
	//重写add_d
	function add_d($object){
		$trialplantemdetail = $object['trialplantemdetail'];
		unset($object['trialplantemdetail']);

		try{
			$this->start_d();

			//新增
			$newId = parent::add_d($object,true);

			//新增从表信息
			$trialplantemdetailDao = new model_hr_baseinfo_trialplantemdetail();
			$trialplantemdetail = $trialplantemdetailDao->batchDeal_d($trialplantemdetail,array('planId' => $newId));
			$trialplantemdetailDao->saveDelBatch($trialplantemdetail);

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			echo $e;
			$this->rollBack();
			return false;
		}
	}

	//重写edit_d
	function edit_d($object){
		$trialplantemdetail = $object['trialplantemdetail'];
		unset($object['trialplantemdetail']);

//		echo "<pre>";print_r($trialplantemdetail);die();
		try{
			$this->start_d();

			//新增
			parent::edit_d($object,true);

			//新增从表信息
			$trialplantemdetailDao = new model_hr_baseinfo_trialplantemdetail();
			$trialplantemdetail = $trialplantemdetailDao->batchDeal_d($trialplantemdetail);
			$trialplantemdetailDao->saveDelBatch($trialplantemdetail);

			$this->commit_d();
			return true;
		}catch(exception $e){
			echo $e;
			$this->rollBack();
			return false;
		}
	}
}
?>
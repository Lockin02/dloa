<?php

/**
 * @author Show
 * @Date 2012年8月28日 星期二 11:32:28
 * @version 1.0
 * @description:任职资格认证评价结果审批表明细 Model层
 */
class model_hr_certifyapply_certifyresultdetail extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_certifyresult_detail";
		$this->sql_map = "hr/certifyapply/certifyresultdetailSql.php";
		parent :: __construct();
	}

	/******************* 配置信息 ***********************/
    //数据字典字段处理
    public $datadictFieldArr = array(
    	'baseLevel','baseGrade','finalLevel','finalGrade'
    );

	/******************* 增删改查 ***********************/

	//重写add_d
	function add_d($object){
		//数据字典处理
		$object = $this->processDatadict($object);

		return parent::add_d($object);
	}

	//重写add_d
	function edit_d($object){
		//数据字典处理
		$object = $this->processDatadict($object);

		return parent::edit_d($object);
	}

	/**
	 * 批量新增
	 */
	function batchAdd_d($object,$addArr){
		//实例化评价表对象
		$assessDao = new model_hr_certifyapply_cassess();
 		//申请单实例化
 		$certifyapplyDao = new model_hr_personnel_certifyapply();

		try{
			$this->start_d();

			foreach($object as $key => $val){

				//数据字典处理
				$val = $this->processDatadict($val);
				//扩展信息合并
				$val = array_merge($val,$addArr);

				parent::add_d($val,true);

				//源单业务处理
				$assessDao->updateStatus_d($val['assessId'],5);

				//更新申请单状态
				$certifyapplyDao->updateStatus_d($val['applyId'],7);
			}

			$this->commit_d();
		}catch(exception $e){
			echo $e;
			$this->rollBack();
			return false;
		}
	}

	/********************* 业务逻辑部分 *********************/
	//对从表批量处理
	function batchProcess_d($object){
		foreach($object as $key => $val){
			$object[$key] = $this->processDatadict($val);
		}
		return $object;
	}

	/**
	 * 根据审核表获取明细
	 */
	function getList_d($resultId){
		$this->searchArr = array(
			'resultId' => $resultId
		);
		$this->asc = false;
		return $this->list_d();
	}
}
?>
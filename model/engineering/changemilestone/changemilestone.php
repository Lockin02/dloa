<?php
/**
 * @author Show
 * @Date 2012年2月10日 星期五 14:05:49
 * @version 1.0
 * @description:项目里程碑变更表 Model层
 */
class model_engineering_changemilestone_changemilestone  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_change_milestone";
		$this->sql_map = "engineering/changemilestone/changemilestoneSql.php";
		parent::__construct ();
	}

	/**************************** 接口方法 ******************/
	/**
     * 获取里程碑所需信息
     */
    function getObjInfo_d($projectId){
    	$serviceesmMilestoneDao = new model_engineering_project_esmproject();
    	$serviceMilestone = $serviceesmMilestoneDao->get_d($projectId);
    	return $serviceMilestone;
    }

	/**
	 * 重写add方法
	 */
	function add_d($object){
		return parent::add_d($object,true);
	}

	/**
	 * 重写编辑方法
	 */
	function edit_d($object){
		return parent::edit_d($object,true);
	}

	/**
	 *  复制项目里程碑信息
	 */
	function addMilestone_d($projectId,$changeId,$versionNo){
		$esmmilestoneDao = new model_engineering_milestone_esmmilestone();
		$esmmilestone = $esmmilestoneDao->getProjectMilestone_d($projectId);

		$esmchangeDao = new model_engineering_change_esmchange();

		//变更表中对应的前置里程碑点id
		$thisPreIdArr = array();

		try{
			$this->start_d();

			if(is_array($esmmilestone)){
				foreach($esmmilestone as $key => $val){

					//数据处理
					$val['changeId'] = $changeId;
					$val['versionNo'] = $versionNo;
					$val['milestoneId'] = $val['id'];
					unset($val['id']);

					if(!empty($val['preMilestoneId'])){
						$val['changePreId'] = $thisPreIdArr[$val['preMilestoneId']];
					}

					//新增
					$thisPreIdArr[$val['milestoneId']] = $this->add_d($val,true);
				}
			}

			//更新变更申请的申请状况
			$esmchangeDao->edit_d(array('id' => $changeId,'isMileStoneChange' => 1));

			$this->commit_d();
			return $changeId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 *  新增变更申请以及复制项目里程碑信息
	 */
	function addMileAndChange_d($projectId){
		$esmmilestoneDao = new model_engineering_milestone_esmmilestone();
		$esmmilestone = $esmmilestoneDao->getProjectMilestone_d($projectId);

		$esmchangeDao = new model_engineering_change_esmchange();
		//变更表中对应的前置里程碑点id
		$thisPreIdArr = array();

		try{
			$this->start_d();

			//增加变更申请
			$esmchangeArr = $esmchangeDao->addSelf_d($projectId,array('isMileStoneChange' => 1));

			//增加项目里程碑
			if(is_array($esmmilestone)){
				foreach($esmmilestone as $key => $val){

					//数据处理
					$val['changeId'] = $esmchangeArr['id'];
					$val['versionNo'] = $esmchangeArr['versionNo'];
					$val['milestoneId'] = $val['id'];
					unset($val['id']);

					if(!empty($val['preMilestoneId'])){
						$val['changePreId'] = $thisPreIdArr[$val['preMilestoneId']];
					}

					//新增
					$thisPreIdArr[$val['milestoneId']] = $this->add_d($val,true);
				}
			}

			$this->commit_d();
			return $esmchangeArr;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 获取变更申请是否要变更里程碑
	 */
	function isChangeMilestone_d($projectId){
		$esmchangeDao = new model_engineering_change_esmchange();
		$rs = $esmchangeDao->find(array('projectId'=>$projectId,'ExaStatus' => '待提交'),null,'isMileStoneChange');
		if(is_array($rs)){
			return $rs['isMileStoneChange'];
		}else{
			return 0;
		}
	}
}
?>
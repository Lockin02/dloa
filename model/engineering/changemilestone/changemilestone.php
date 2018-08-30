<?php
/**
 * @author Show
 * @Date 2012��2��10�� ������ 14:05:49
 * @version 1.0
 * @description:��Ŀ��̱������ Model��
 */
class model_engineering_changemilestone_changemilestone  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_change_milestone";
		$this->sql_map = "engineering/changemilestone/changemilestoneSql.php";
		parent::__construct ();
	}

	/**************************** �ӿڷ��� ******************/
	/**
     * ��ȡ��̱�������Ϣ
     */
    function getObjInfo_d($projectId){
    	$serviceesmMilestoneDao = new model_engineering_project_esmproject();
    	$serviceMilestone = $serviceesmMilestoneDao->get_d($projectId);
    	return $serviceMilestone;
    }

	/**
	 * ��дadd����
	 */
	function add_d($object){
		return parent::add_d($object,true);
	}

	/**
	 * ��д�༭����
	 */
	function edit_d($object){
		return parent::edit_d($object,true);
	}

	/**
	 *  ������Ŀ��̱���Ϣ
	 */
	function addMilestone_d($projectId,$changeId,$versionNo){
		$esmmilestoneDao = new model_engineering_milestone_esmmilestone();
		$esmmilestone = $esmmilestoneDao->getProjectMilestone_d($projectId);

		$esmchangeDao = new model_engineering_change_esmchange();

		//������ж�Ӧ��ǰ����̱���id
		$thisPreIdArr = array();

		try{
			$this->start_d();

			if(is_array($esmmilestone)){
				foreach($esmmilestone as $key => $val){

					//���ݴ���
					$val['changeId'] = $changeId;
					$val['versionNo'] = $versionNo;
					$val['milestoneId'] = $val['id'];
					unset($val['id']);

					if(!empty($val['preMilestoneId'])){
						$val['changePreId'] = $thisPreIdArr[$val['preMilestoneId']];
					}

					//����
					$thisPreIdArr[$val['milestoneId']] = $this->add_d($val,true);
				}
			}

			//���±�����������״��
			$esmchangeDao->edit_d(array('id' => $changeId,'isMileStoneChange' => 1));

			$this->commit_d();
			return $changeId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 *  ������������Լ�������Ŀ��̱���Ϣ
	 */
	function addMileAndChange_d($projectId){
		$esmmilestoneDao = new model_engineering_milestone_esmmilestone();
		$esmmilestone = $esmmilestoneDao->getProjectMilestone_d($projectId);

		$esmchangeDao = new model_engineering_change_esmchange();
		//������ж�Ӧ��ǰ����̱���id
		$thisPreIdArr = array();

		try{
			$this->start_d();

			//���ӱ������
			$esmchangeArr = $esmchangeDao->addSelf_d($projectId,array('isMileStoneChange' => 1));

			//������Ŀ��̱�
			if(is_array($esmmilestone)){
				foreach($esmmilestone as $key => $val){

					//���ݴ���
					$val['changeId'] = $esmchangeArr['id'];
					$val['versionNo'] = $esmchangeArr['versionNo'];
					$val['milestoneId'] = $val['id'];
					unset($val['id']);

					if(!empty($val['preMilestoneId'])){
						$val['changePreId'] = $thisPreIdArr[$val['preMilestoneId']];
					}

					//����
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
	 * ��ȡ��������Ƿ�Ҫ�����̱�
	 */
	function isChangeMilestone_d($projectId){
		$esmchangeDao = new model_engineering_change_esmchange();
		$rs = $esmchangeDao->find(array('projectId'=>$projectId,'ExaStatus' => '���ύ'),null,'isMileStoneChange');
		if(is_array($rs)){
			return $rs['isMileStoneChange'];
		}else{
			return 0;
		}
	}
}
?>
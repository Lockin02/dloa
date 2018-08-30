<?php

/**
 * @author Show
 * @Date 2012��7��27�� ������ 16:23:53
 * @version 1.0
 * @description:��Ŀ�����Ա Model��
 */
class model_engineering_activity_esmactmember extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_project_activitymember";
		$this->sql_map = "engineering/activity/esmactmemberSql.php";
		parent :: __construct();
	}

	/******************* ��ɾ�Ĳ� ***********************/
	/**
	 * ��������
	 */
	function batchDeal_d($object,$activityId = null,$activityName = null){
		if(empty($object)){
			return false;
		}
		//ʵ������Ŀ��Ա����
		$projectmemberDao = new model_engineering_member_esmmember();

		try{
			$this->start_d();

			$returnObjs = array ();
			foreach ( $object as $key => $val ) {
				if($activityId){
					$val['activityId'] = $activityId;
					$val['activityName'] = $activityName;
				}
				$val=$this->addCreateInfo($val);
				$isDelTag=isset($val ['isDelTag'])?$val ['isDelTag']:NULL;
				$updateArr = $val;

				if (empty ( $val ['id'] ) && $isDelTag== 1) {

				} else if (empty ( $val ['id'] )) {
					if(empty($val['memberId'])){
						continue;
					}else{

					$id = $this->add_d ( $val );
					$val ['id'] = $id;
					array_push ( $returnObjs, $val );
					}
				} else if ($isDelTag == 1) {
					$this->deletes ( $val ['id'] );
				} else {
					$this->edit_d ( $val );
					array_push ( $returnObjs, $val );
				}

				/**
				 * ������Ŀ��Ա���� - ���ɾ���������Ա�������������Ա���Ÿ�����Ŀ��Ա����Ϣ
				 */
//				if($isDelTag == 1 || isset($id) ){
//					$projectmemberDao->updateMemberInfo_d($updateArr,$this);
//				}
			}

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ���������Ա��ʵ�ʽ�������
	 */
	function updateMemberEndDate_d($activityId){
		$sql = "update ". $this->tbl_name . " a
				inner join
				(
					select
						c.createId,max(c.executionDate) as lastDate
					from
						oa_esm_worklog c
					where
						c.activityId = $activityId
					group by c.createId
				) c
				on a.memberId = c.createId
				set a.actEndDate = c.lastDate
			where a.activityId = $activityId";
		try{
			$this->_db->query($sql);
			return true;
		}catch(exception $e){
			throw $e;
			return false;
		}
	}

	/********************** ҵ���� *********************/

	//��ȡ��ǰ��Ա��ǰ��Ŀ����������
	function getAllTask_d($projectId,$memberId){
		$this->searchArr = array(
			'projectId' => $projectId,
			'memberId' => $memberId
		);
		return $this->list_d();
	}

	//�жϳ�Ա�Ƿ��Ѿ�����Ŀ������
	function isExsistActivity_d($projectId,$memberId){
		$this->searchArr = array(
			'projectId' => $projectId,
			'memberIdArr' => $memberId
		);
		$rs = $this->list_d();
		if($rs){
			$memberArr = array();
			foreach($rs as $key => $val){
				array_push($memberArr,$val['memberName']);
			}
			return implode($memberArr,',');
		}else{
			return 0;
		}
	}

	//���������Ա��ʵ�ʹ�����
	function updateDayInfo_d($activityId,$memberId,$actDays){
		$sql = "update ". $this->tbl_name . " set actDays = $actDays,actCostDays = actDays * coefficient,actCost = actDays * price where activityId = $activityId and memberId = '$memberId'";
		try{
			$this->_db->query($sql);
			return true;
		}catch(exception $e){
			throw $e;
			return false;
		}
	}

	//��ȡ��������
	function getActivity_d($activityId){
		$esmactivityDao = new model_engineering_activity_esmactivity();
		return $esmactivityArr = $esmactivityDao->get_d($activityId);
	}

	//���������Ա
	function editMember_d($object){
		try{
			$this->start_d();

			//��������
			$this->saveDelBatch($object['esmactmember']);

			//ѭ����ȡ��Ա
			$memberArr = $this->getMemberArr_d($object['esmactmember']);

			//����������Ϣ
			$esmactivityDao = new model_engineering_activity_esmactivity();
			$memberArr['id'] = $object['activityId'];
			$esmactivityDao->editOrg_d($memberArr);

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	//���������Ա���鷵�س�Ա����
	function getMemberArr_d($object){
		if($object){
			$memberNameArr = $memberIdArr = array();
			foreach($object as $key => $val){
				if(!isset($val['isDelTag'])){
					array_push($memberNameArr,$val['memberName']);
					array_push($memberIdArr,$val['memberId']);
				}
			}
			return array(
				'memberName'=> implode($memberNameArr,','),
				'memberId' => implode($memberIdArr,',')
			);
		}else{
			return array(
				'memberName'=> '',
				'memberId' => ''
			);
		}
	}
}
?>
<?php

/**
 * @author Show
 * @Date 2012年7月27日 星期五 16:23:53
 * @version 1.0
 * @description:项目任务成员 Model层
 */
class model_engineering_activity_esmactmember extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_project_activitymember";
		$this->sql_map = "engineering/activity/esmactmemberSql.php";
		parent :: __construct();
	}

	/******************* 增删改查 ***********************/
	/**
	 * 批量处理
	 */
	function batchDeal_d($object,$activityId = null,$activityName = null){
		if(empty($object)){
			return false;
		}
		//实例化项目成员对象
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
				 * 处理项目成员部分 - 如果删除了任务成员或者新增任务成员，才更新项目成员的信息
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
	 * 更新任务成员的实际结束日期
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

	/********************** 业务处理 *********************/

	//获取当前人员当前项目的所有任务
	function getAllTask_d($projectId,$memberId){
		$this->searchArr = array(
			'projectId' => $projectId,
			'memberId' => $memberId
		);
		return $this->list_d();
	}

	//判断成员是否已经在项目任务中
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

	//更新任务成员的实际工作量
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

	//获取任务内容
	function getActivity_d($activityId){
		$esmactivityDao = new model_engineering_activity_esmactivity();
		return $esmactivityArr = $esmactivityDao->get_d($activityId);
	}

	//保存任务成员
	function editMember_d($object){
		try{
			$this->start_d();

			//批量保存
			$this->saveDelBatch($object['esmactmember']);

			//循环获取人员
			$memberArr = $this->getMemberArr_d($object['esmactmember']);

			//更新任务信息
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

	//传入任务成员数组返回成员数组
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
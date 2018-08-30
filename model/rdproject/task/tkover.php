<?php
/*
 * @author huangzhifan
 * @Date 2010-9-15
 * @copyright (c) YXKJ Company.
 * @description:项目任务参与人Model
 *
 */
 class model_rdproject_task_tkover extends model_base {


	function __construct() {
		$this->tbl_name = "oa_rd_task_over";
		$this->sql_map = "rdproject/task/tkoverSql.php";
		parent::__construct ();
	}

	/* ---------------------------------页面模板显示调用------------------------------------------*/



	/* -----------------------------------业务接口调用-------------------------------------------*/
	/*
	 * 新增任务终止信息,同时更新项目任务的状态
	 */
	function add_d($object){
		try{
			$this->start_d();
			$object['auditDate']=date ( "Y-m-d H:i:s" );
			$object['auditName']=$_SESSION['USERNAME'];
			$object['auditId']=$_SESSION['USER_ID'];
		$rdTaskDao=new model_rdproject_task_rdtask();
		$rdTask['id']=$object['taskId'];
		$rdTask['status']="QZZZ";
		$rdTaskDao->updateById($rdTask);

		$id=parent::add_d($object);
		$this->commit_d();
		return id;
		}
		catch(Exception $e)
		{
			$this->rollBack();
			return null;
		}
	}
	/**
	 * @desription 我接受的任务右键提交任务数据获得
	 * @param tags
	 * @date 2010-9-29 下午07:43:06
	 */
	function getSubmitTkInfo_d($taskId) {
		//找出本表中的数据
		$taskinfoDao=new model_rdproject_task_rdtask();
		$taskinfo=$taskinfoDao->getEditTaskInfo_d($taskId);
		return $taskinfo;
	}

	/*
	 * 通过任务id 获取所提交id
	 */
	function getOverLastByTkId($taskId){
		$conditions=" taskId=".$taskId." and auditId is null";
//		$taskInfoDao = new model_rdproject_task_rdtask();
//		$upword=$taskInfoDao->get_d(['putWorkload']);

		//print_r($conditions);
		return parent::find( $conditions);

	}

	function getTkOverLastByTkId($taskId){
		$conditions=" taskId=".$taskId." and auditId is null";
//		$taskInfoDao = new model_rdproject_task_rdtask();
//		$upword=$taskInfoDao->get_d(['putWorkload']);

		//print_r($conditions);
		return parent::get_table_fields($this->tbl_name, $conditions, "id");

	}
/*提交任务保存数据*/
	function addSubmitInfo_d($object){
		try{
			$this->start_d();
			/*----------设置提交信息-----------------*/
			$object['subDate']=day_date;
			$object['subName']=$_SESSION['USERNAME'];
			$object['subId']=$_SESSION['USER_ID'];
			$id=parent::add_d($object,true);

			if( $object['email']['issend'] == 'y'){
				$addMsg = '您收到了任务名为：<<' . $object['name'] . '>>的审核申请，请尽快查看并审核！';
				$emailDao = new model_common_mail();
				$emailInfo = $emailDao->batchEmail("y",$_SESSION['USERNAME'],$_SESSION['EMAIL'],$this->tbl_name,'提交',$object['name'],$object['email']['TO_ID'],$addMsg,'1');
			}

			$taskinfo=new model_rdproject_task_rdtask();
			$taskinfo->updateTaskStatus_d($object['taskId'],"JXZ","DSH");

			$taskauditDao=new model_rdproject_task_tkaudituser();
			$tkactuser=array(
				'taskId'=> $object['taskId']
			);
			$taskauditDao->delete($tkactuser);
			if( $object['task']['auditId'] ){
				$tkactuser['auditId']=$object['task']['auditId'];
				$tkactuser['auditUser']=$object['task']['auditName'];
				$taskauditDao->add_d($tkactuser);
			}
			$this->commit_d();
			$this->rollBack();
			return $id;
		}catch(Exception $e)
		{
			$this->rollBack();
			return null;
		}
	}
	/**
	 * @desription 审核任务保存数据
	 * @param tags
	 * @date 2010-10-2 下午04:53:40
	 */
	function addAuditInfo_d($object) {
		try{
			$this->start_d();
			$object['auditDate']=date ( "Y-m-d H:i:s" );
			$object['auditName']=$_SESSION['USERNAME'];
			$object['auditId']=$_SESSION['USER_ID'];

		/*s:根据审核结果 更新任务状态; 通过时并更新任务的实际完成时间*/
		$taskinfoDao=new model_rdproject_task_rdtask();
		$auditS=$object['auditStatus'];
		if($auditS==0){
			$lastTkInfo = $taskinfoDao->get_d ( $object['taskId'] );//任务信息
			$tksubmitinfo=$this->get_d($object['id']);//最新提交任务信息

			/*s:-----------更新任务信息---------------*/
			$taskinfoDao->updateTaskStatus_d($object['taskId'],"DSH","TG",$lastTkInfo,$tksubmitinfo,$object);
			/*e:-----------更新任务信息---------------*/

			/*s:---------更新任务里程碑点状态(里程碑点底下的任务全部完成后才能更新其状态)------------*/
			if ((int)$lastTkInfo ['isStone'] === 0) {
                $stoneDao = new model_rdproject_milestone_rdmilespoint ();
                $stoneDao->updateMilestonePeriod_d($lastTkInfo ['stoneId']);
			}
			/*e:--------更新任务里程碑点状态-------------*/


			/*s:--------更新计划已投入工作量--------------*/
				if(!empty($lastTkInfo['planId']))
				{
					$planDao=new model_rdproject_plan_rdplan();
					$planDao->addPutLoad($lastTkInfo['planId'],$object['informTime'],$lastTkInfo['effortRate']);
				}

			/*e:-------更新计划已投入工作量---------------*/

			/*s:-------更新项目的工作量值------------*/
			if(isset($lastTkInfo['projectId'])){
				$rdProjectDao=new model_rdproject_project_rdproject();
				$rdProjectDao->rpAddWorkloadById_d($lastTkInfo['projectId'],$object['informTime']);
			}

			/*e:-------更新项目的工作量值------------*/
		}else{
			$taskinfoDao->updateTaskStatus_d($object['taskId'],"DSH","WTG");
		}
		/*e:根据审核结果 更新任务状态;通过时并更新任务的实际完成时间*/

		$id = parent::edit_d($object,true);//不能挪前  保存审核信息
//		$this->rollBack();
		$this->commit_d();
		return $id;
		}
		catch(Exception $e)
		{
			$this->rollBack();
			return null;
		}
	}
 }
?>

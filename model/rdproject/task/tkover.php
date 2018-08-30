<?php
/*
 * @author huangzhifan
 * @Date 2010-9-15
 * @copyright (c) YXKJ Company.
 * @description:��Ŀ���������Model
 *
 */
 class model_rdproject_task_tkover extends model_base {


	function __construct() {
		$this->tbl_name = "oa_rd_task_over";
		$this->sql_map = "rdproject/task/tkoverSql.php";
		parent::__construct ();
	}

	/* ---------------------------------ҳ��ģ����ʾ����------------------------------------------*/



	/* -----------------------------------ҵ��ӿڵ���-------------------------------------------*/
	/*
	 * ����������ֹ��Ϣ,ͬʱ������Ŀ�����״̬
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
	 * @desription �ҽ��ܵ������Ҽ��ύ�������ݻ��
	 * @param tags
	 * @date 2010-9-29 ����07:43:06
	 */
	function getSubmitTkInfo_d($taskId) {
		//�ҳ������е�����
		$taskinfoDao=new model_rdproject_task_rdtask();
		$taskinfo=$taskinfoDao->getEditTaskInfo_d($taskId);
		return $taskinfo;
	}

	/*
	 * ͨ������id ��ȡ���ύid
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
/*�ύ���񱣴�����*/
	function addSubmitInfo_d($object){
		try{
			$this->start_d();
			/*----------�����ύ��Ϣ-----------------*/
			$object['subDate']=day_date;
			$object['subName']=$_SESSION['USERNAME'];
			$object['subId']=$_SESSION['USER_ID'];
			$id=parent::add_d($object,true);

			if( $object['email']['issend'] == 'y'){
				$addMsg = '���յ���������Ϊ��<<' . $object['name'] . '>>��������룬�뾡��鿴����ˣ�';
				$emailDao = new model_common_mail();
				$emailInfo = $emailDao->batchEmail("y",$_SESSION['USERNAME'],$_SESSION['EMAIL'],$this->tbl_name,'�ύ',$object['name'],$object['email']['TO_ID'],$addMsg,'1');
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
	 * @desription ������񱣴�����
	 * @param tags
	 * @date 2010-10-2 ����04:53:40
	 */
	function addAuditInfo_d($object) {
		try{
			$this->start_d();
			$object['auditDate']=date ( "Y-m-d H:i:s" );
			$object['auditName']=$_SESSION['USERNAME'];
			$object['auditId']=$_SESSION['USER_ID'];

		/*s:������˽�� ��������״̬; ͨ��ʱ�����������ʵ�����ʱ��*/
		$taskinfoDao=new model_rdproject_task_rdtask();
		$auditS=$object['auditStatus'];
		if($auditS==0){
			$lastTkInfo = $taskinfoDao->get_d ( $object['taskId'] );//������Ϣ
			$tksubmitinfo=$this->get_d($object['id']);//�����ύ������Ϣ

			/*s:-----------����������Ϣ---------------*/
			$taskinfoDao->updateTaskStatus_d($object['taskId'],"DSH","TG",$lastTkInfo,$tksubmitinfo,$object);
			/*e:-----------����������Ϣ---------------*/

			/*s:---------����������̱���״̬(��̱�����µ�����ȫ����ɺ���ܸ�����״̬)------------*/
			if ((int)$lastTkInfo ['isStone'] === 0) {
                $stoneDao = new model_rdproject_milestone_rdmilespoint ();
                $stoneDao->updateMilestonePeriod_d($lastTkInfo ['stoneId']);
			}
			/*e:--------����������̱���״̬-------------*/


			/*s:--------���¼ƻ���Ͷ�빤����--------------*/
				if(!empty($lastTkInfo['planId']))
				{
					$planDao=new model_rdproject_plan_rdplan();
					$planDao->addPutLoad($lastTkInfo['planId'],$object['informTime'],$lastTkInfo['effortRate']);
				}

			/*e:-------���¼ƻ���Ͷ�빤����---------------*/

			/*s:-------������Ŀ�Ĺ�����ֵ------------*/
			if(isset($lastTkInfo['projectId'])){
				$rdProjectDao=new model_rdproject_project_rdproject();
				$rdProjectDao->rpAddWorkloadById_d($lastTkInfo['projectId'],$object['informTime']);
			}

			/*e:-------������Ŀ�Ĺ�����ֵ------------*/
		}else{
			$taskinfoDao->updateTaskStatus_d($object['taskId'],"DSH","WTG");
		}
		/*e:������˽�� ��������״̬;ͨ��ʱ�����������ʵ�����ʱ��*/

		$id = parent::edit_d($object,true);//����Ųǰ  ���������Ϣ
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

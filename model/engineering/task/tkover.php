<?php
/*
 * @author huangzhifan
 * @Date 2010-9-15
 * @copyright (c) YXKJ Company.
 * @description:项目任务参与人Model
 *
 */
 class model_engineering_task_tkover extends model_base {


	function __construct() {
		$this->tbl_name = "oa_esm_task_over";
		$this->sql_map = "engineering/task/tkoverSql.php";
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
		$rdTaskDao=new model_engineering_task_protask();
		$proTask['id']=$object['taskId'];
		$proTask['status']="QZZZ";

		$rdTaskDao->updateById($proTask);

		$id=parent::updateById($object);
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
	 * 提交任务数据获得
	 */
		function Putadd_d($object){
		try{
			$this->start_d();
			$object['subDate']=date ( "Y-m-d H:i:s" );
			$object['subName']=$_SESSION['USERNAME'];
			$object['subId']=$_SESSION['USER_ID'];
		$rdTaskDao=new model_engineering_task_protask();
		$proTask['id']=$object['taskId'];
		$proTask['status']="DSH";

		$rdTaskDao->updateById($proTask);

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

	/*
	 * 通过任务id 获取所提交id
	 */
	function getOverLastByTkId($taskId){
		print_r($taskId);
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

	/*
	 * @desription 审核任务保存数据
	 */
	function addAuditInfo_d($object){
		try{
			$this->start_d();
			$object['auditDate']=date ( "Y-m-d H:i:s" );
			$object['auditName']=$_SESSION['USERNAME'];
			$object['auditId']=$_SESSION['USER_ID'];
		$rdTaskDao=new model_engineering_task_protask();
		$auditS=$object['auditStatus'];
//		$proTask['id']=$object['taskId'];
//		$proTask['status']="TG";
        if($auditS==0){
            $proTask['id']=$object['taskId'];
		    $proTask['status']="TG";
		}else if($auditS==1){
			$proTask['id']=$object['taskId'];
		    $proTask['status']="WTG";
		}
		$rdTaskDao->updateById($proTask);

		$id=parent::edit_d($object);
		$this->commit_d();
		return id;
		}
		catch(Exception $e)
		{
			$this->rollBack();
			return null;
		}
	}
//	function addAuditInfo_d($object) {
//		try{
//			$this->start_d();
//			$object['auditDate']=date ( "Y-m-d H:i:s" );
//			$object['auditName']=$_SESSION['USERNAME'];
//			$object['auditId']=$_SESSION['USER_ID'];
//
//		/*s:根据审核结果 更新任务状态; 通过时并更新任务的实际完成时间*/
//		$taskinfoDao=new model_engineering_task_protask();
//		$auditS=$object['auditStatus'];
//		if($auditS==0){
//            $proTask['id']=$object['taskId'];
//		    $proTask['status']="WTG";
//		}else{
//			$proTask['id']=$object['taskId'];
//		    $proTask['status']="TG";
//		}
//		/*e:根据审核结果 更新任务状态;通过时并更新任务的实际完成时间*/
//
//		$id = parent::edit_d($object,true);//不能挪前  保存审核信息
//
//		$this->commit_d();
//		return $id;
//		}
//		catch(Exception $e)
//		{
//			$this->rollBack();
//			return null;
//		}
//	}

 }
?>

<?php
/*
 * Created on 2010-9-27
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class controller_rdproject_task_stophistory extends controller_base_action {

	/*
	 * @desription 构造函数
	 * @date 2010-9-27 下午02:31:46
	 */
	function __construct() {
		$this->objName = "stophistory";
		$this->objPath = "rdproject_task";
		parent::__construct ();
	}


	/*
	 * @desription 跳转到我接受的任务申请暂停页面
	 * @date 2011年8月8日 13:45:17
	 * zengzx
	 */
	function c_toApplyToStop() {
			$this->show->assign("lastStatus",$_GET['status']);
			$this->show->assign("taskId",$_GET['id']);
			$taskDao = new model_rdproject_task_rdtask();
			$beginTime = $taskDao->get_table_fields( $taskDao->tbl_name,'id='.$_GET['id'],"planBeginDate" );
			$this->show->assign("beginTime",$beginTime);
			$this->display ( 'apply' );
	}

	/*
	 * @desription 跳转到我分配的任务暂停页面
	 * @date 2010-9-27 下午02:31:46
	 */
	function c_toStopTask() {
			$this->show->assign("lastStatus",$_GET['status']);
			$this->show->assign("taskId",$_GET['id']);
			$taskDao = new model_rdproject_task_rdtask();
			$beginTime = $taskDao->get_table_fields( $taskDao->tbl_name,'id='.$_GET['id'],"planBeginDate" );
			$this->show->assign("beginTime",$beginTime);
			$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}
	/*
	 * 跳转到恢复任务页面
	 */
	 function c_toReBackTask(){
			$this->show->assign("taskId",$_GET['id']);
			$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );

	 }


	/*
	 * 暂停任务,新增任务暂停记录
	 */
	function c_add(){
		$stophistory=$_POST [$this->objName];
		if ($this->service->add_d($stophistory)) {
			msg ( '已申请暂停！' );
			$rdtaskDao=new model_rdproject_task_rdtask();
			$task = $rdtaskDao->get_d ( $stophistory ['taskId'] );
			$task ['operType_'] = "申请暂停任务";
			$task ['operateLog_'] = "申请原因：".$stophistory['applyReason'];
			$this->service->tbl_name = $rdtaskDao->tbl_name;//设置为任务操作编码（使用任务表名做编码）
			$this->behindMethod ( $task );
		}
	}

	/*
	 * 暂停任务,新增任务暂停记录
	 */
	function c_stop(){
		$stophistory=$_POST [$this->objName];
		if($stophistory['lastStatus'] == 'SQZT'){
			unset($stophistory['lastStatus']);
			if( $this->service->stopByApply_d($stophistory) ){
				msg('暂停成功');
			};
		}else{
			if ($this->service->stop_d($stophistory)) {
				msg ( '暂停成功！' );
			}
		}
		$rdtaskDao=new model_rdproject_task_rdtask();
		$task = $rdtaskDao->get_d ( $stophistory ['taskId'] );
		$task ['operType_'] = "暂停任务";
		$task ['operateLog_'] = "暂停原因：".$stophistory['stopReason'];
		$this->service->tbl_name = $rdtaskDao->tbl_name;//设置为任务操作编码（使用任务表名做编码）
		$this->behindMethod ( $task );
	}

	/*
	 * 恢复任务，修改暂停记录
	 */
	 function c_edit(){
	 	$stophistory=$_POST [$this->objName];
	 	if ($this->service->edit_d($stophistory)) {
			msg ( '恢复完成！' );
			$rdtaskDao=new model_rdproject_task_rdtask();
			$task = $rdtaskDao->get_d ( $stophistory ['taskId'] );
			$task ['operType_'] = "恢复任务";
			$task ['operateLog_'] = "恢复描述:".$stophistory['remark'];
			$this->service->tbl_name = $rdtaskDao->tbl_name;//设置为任务操作编码（使用任务表名做编码）
			$this->behindMethod ( $task );
		}
	 }

	/*
	 * 打回暂停申请
	 */
	 function c_fightback(){
	 	$taskId = $_GET['id'];
	 	if ($this->service->fightback_d($taskId)) {
			$rdtaskDao=new model_rdproject_task_rdtask();
			$task = $rdtaskDao->get_d ( $taskId );
			$task ['operType_'] = "打回暂停申请";
			$this->service->tbl_name = $rdtaskDao->tbl_name;//设置为任务操作编码（使用任务表名做编码）
			$this->behindMethod ( $task );
			echo 1;
		}else{
			echo 0;
		}
	 }
 }
?>

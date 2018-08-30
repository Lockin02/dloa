<?php

/*
 * @author huangzhifan
 * @Date 2010-9-15
 * @copyright (c) YXKJ Company.
 * @description: 项目任务高级信息action
 *
 */
class controller_rdproject_task_tkover extends controller_base_action {

	function __construct() {
		$this->objName = "tkover";
		$this->objPath = "rdproject_task";
		parent::__construct ();
	}

	/*
	 *跳转到新增强制终止页面
	 */
	function c_toAdd() {
		$this->show->assign ( "taskId", $_GET ['id'] );
		$this->showDatadicts ( array ('finishGrade' => 'XMRWZPDJ' ),'common' );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}
	/*
	 * 新增任务终止信息
	 */
	function c_add() {
		$tkover = $_POST [$this->objName];
		$id=$this->service->add_d ( $tkover );
		/*更新任务完成率为100%*/
		$tkinfoDao=new model_rdproject_task_rdtask();
		$tkinfo['id']=$tkover['taskId'];
		$tkinfo['effortRate']=100;
		$tkinfoDao->updateById($tkinfo);
		if ($id) {
			msg ( '终止成功！' );
			$rdtaskDao = new model_rdproject_task_rdtask ();
			$task = $rdtaskDao->get_d ( $tkover ['taskId'] );
			$task ['operType_'] = "终止任务【" . $task ['name'] . "】";
			$this->service->tbl_name = $rdtaskDao->tbl_name;//设置为任务操作编码（使用任务表名做编码）
			$this->behindMethod ( $task );
		}
	}
	/**
	 * @desription 跳转到我接受的任务右键提交任务
	 * @param tags
	 * @date 2010-9-29 下午07:19:40
	 */
	function c_toSumbit() {
		$taskinfo = $this->service->getSubmitTkInfo_d ( $_GET ['id'] );
		$this->showDatadicts ( array ('selfGrade' => 'XMRWZPDJ' ) );
		foreach ( $taskinfo as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->assign( 'taskType',$this->getDataNameByCode( $taskinfo['taskType'] ) );
		$this->assign( 'priority',$this->getDataNameByCode( $taskinfo['priority'] ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-sumbit' );
	}
	/**
	 * @desription 跳转到审核页面
	 * @param tags
	 * @date 2010-10-2 下午04:33:25
	 */
	function c_toAudit() {
		//$tkover = $_POST [$this->objName];
		//$this->show->assign ( "taskId", $_GET ['taskId'] );
		$overinfo = $this->service->getOverLastByTkId ( $_GET ['taskId'] );
		$overinfo['selfGrade'] = $this->getDataNameByCode($overinfo['selfGrade']);
		$taskInfoDao = new model_rdproject_task_rdtask();
		foreach ( $overinfo as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('finishGrade' => 'XMRWZPDJ' ) );
		$tkinfo=$taskInfoDao->get_d( $_GET ['taskId']);
		$this->show->assign ( "informTime", $tkinfo['putWorkload'] );
		$this->show->assign ( "appraiseWorkload", $tkinfo['appraiseWorkload'] );//估计工作量
		$this->show->assign ( "actWorkload", $tkinfo['actWorkload'] );//实际工作量
		$this->show->display ( $this->objPath . '_' . $this->objName . '-audit' );
	}

	/**
	 * @desription 审批信息保存
	 * @param tags
	 * @date 2010-10-2 下午04:50:04
	 */
	function c_addAuditTkinfo() {
		$tkauditinfo=$_POST [$this->objName];
		$id = $this->service->addAuditInfo_d ($tkauditinfo, true );
		if ($id) {
			msg ( '审批成功' );
			$rdtaskDao=new model_rdproject_task_rdtask();
			$task ['id']=$tkauditinfo['taskId'];
			$task ['operType_'] = "审批任务";
			$this->service->tbl_name = $rdtaskDao->tbl_name;//设置为任务操作编码（使用任务表名做编码）
			$this->behindMethod ( $task );
		}
	}
	/**
	 * @desription 提交任务保存数据
	 * @param tags
	 * @date 2010-10-2 上午10:45:23
	 */
	function c_addSubmitTkinfo() {
		$tksubmitinfo=$_POST [$this->objName];
		$id = $this->service->addSubmitInfo_d ($tksubmitinfo , true );

		if ($id) {
			msg ( '提交任务成功' );
			$rdtaskDao=new model_rdproject_task_rdtask();
			$task ['id']=$tksubmitinfo['taskId'];
			$task ['operType_'] = "提交任务";
			$this->service->tbl_name = $rdtaskDao->tbl_name;//设置为任务操作编码（使用任务表名做编码）
			$this->behindMethod ( $task );
		}
	}

}
?>

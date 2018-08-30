<?php
/**
 * @author show
 * @Date 2013年9月22日 14:45:40
 * @version 1.0
 * @description:项目周任务进度控制层
 */
class controller_engineering_weekreport_weektask extends controller_base_action {

	function __construct() {
		$this->objName = "weektask";
		$this->objPath = "engineering_weekreport";
		parent :: __construct();
	}

	/**
	 * 获取任务信息
	 */
	function c_getWeekTask(){
		$projectId = isset($_REQUEST['projectId']) ? $_REQUEST['projectId'] : "";
		$weekNo = isset($_REQUEST['weekNo']) ? $_REQUEST['weekNo'] : "";
		$mainId = isset($_REQUEST['mainId']) ? $_REQUEST['mainId'] : "";
		$WeekTaskInfo = $this->service->getNewWeekTask_d($projectId,$weekNo);
		//编辑页面，更新表数据
		if(!empty($mainId)){
			$this->service->update_d($mainId,$WeekTaskInfo);
		}
		echo (util_jsonUtil::iconvGB2UTF($this->service->showWeekTask_d($WeekTaskInfo)));
	}

	/**
	 * 查看周状况数据
	 */
	function c_viewWeekTask(){
		$projectId = isset($_REQUEST['projectId']) ? $_REQUEST['projectId'] : "";
		$weekNo = isset($_REQUEST['weekNo']) ? $_REQUEST['weekNo'] : "";
		$mainId = isset($_REQUEST['mainId']) ? $_REQUEST['mainId'] : "";
		//获取周报审批状态
		$statusreportDao = new model_engineering_project_statusreport();
		$statusInfo = $statusreportDao->getExaStatus_d($mainId);
		if($statusInfo){
			$WeekTaskInfo = $this->service->getNewWeekTask_d($projectId,$weekNo);
			//更新表数据
			$this->service->update_d($mainId,$WeekTaskInfo);
		}else{
			$WeekTaskInfo = $this->service->getWeekTask_d(null,null,$mainId);
		}
		echo (util_jsonUtil::iconvGB2UTF($this->service->viewWeekTask_d($WeekTaskInfo)));
	}
	
	/**
	 * 获取任务信息_新窗口
	 */
	function c_getNewWeekTask(){	
		$this->assignFunc($_GET);
		$this->view('task');		
	}
}
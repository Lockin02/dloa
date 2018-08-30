<?php
/**
 * @author show
 * @Date 2013年10月17日 15:38:39
 * @version 1.0
 * @description:项目周预决算控制层
 */
class controller_engineering_weekreport_weekfee extends controller_base_action {

	function __construct() {
		$this->objName = "weekfee";
		$this->objPath = "engineering_weekreport";
		parent :: __construct();
	}

	/**
	 * 获取任务信息 - 查看和新增、编辑业务无差异
	 */
	function c_getWeek(){
		$projectId = isset($_REQUEST['projectId']) ? $_REQUEST['projectId'] : "";
		$weekNo = isset($_REQUEST['weekNo']) ? $_REQUEST['weekNo'] : "";
		$mainId = isset($_REQUEST['mainId']) ? $_REQUEST['mainId'] : "";
// 		$weeklogInfo = $this->service->getWeek_d($projectId,$weekNo,$mainId);
		$weeklogInfo = $this->service->getNew_d($projectId,$weekNo);
		//编辑，查看页面执行
		if(!empty($mainId)){
			//获取周报审批状态
			$statusreportDao = new model_engineering_project_statusreport();
			$statusInfo = $statusreportDao->getExaStatus_d($mainId);
			if($statusInfo){
				//更新表数据
				$this->service->update_d($mainId,$weeklogInfo);
			}
		}
		exit(util_jsonUtil::iconvGB2UTF($this->service->showWeek_d($weeklogInfo)));
	}
	
	/**
	 * 获取预决算信息_新窗口
	 */
	function c_getNewWeek(){
		$this->assignFunc($_GET);
		$this->view('fee');		
	}
}
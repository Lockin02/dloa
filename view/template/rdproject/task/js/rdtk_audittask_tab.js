var plus = "&varName=mtkTabInfo&jsUrl=view/template/rdproject/task/js/rdtk_audittask_tab.js&objTable=oa_rd_task&objId=";
var jsUrl="view/template/rdproject/task/js/rdtk_audittask_tab.js";
var mtkTabInfo = [{
		"name":"审核任务",
		"url"  : "?model=rdproject_task_tkover&action=toAudit&taskId=",
		"choose" : "1"
	},{
		"name":"前置任务详细",
		"url" : "?model=rdproject_task_tkfront&action=toViewFrontDetail&jsUrl="+jsUrl+"&taskId=",
		"choose" : "2"
	},{
		"name":"执行日报",
		"url" : "?model=rdproject_worklog_rdworklog&action=worklogInTask&jsUrl="+jsUrl+"&id=",
		"choose" : "3"
	},{
		"name":"变更记录",
		"url" : "?model=log_change_change&action=page&tabId=4" + plus,
		"choose" : "4"
	},{
		"name":"操作记录",
		"url" : "?model=log_operation_operation&action=page&tabId=5" + plus,
		"choose" : "5"
	},{
		"name":"附件",
		"url" : "?model=file_uploadfile_management&action=rdpage&tabId=6"
					+ plus,
		"choose" : "6"
	}

];
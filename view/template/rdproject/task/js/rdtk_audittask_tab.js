var plus = "&varName=mtkTabInfo&jsUrl=view/template/rdproject/task/js/rdtk_audittask_tab.js&objTable=oa_rd_task&objId=";
var jsUrl="view/template/rdproject/task/js/rdtk_audittask_tab.js";
var mtkTabInfo = [{
		"name":"�������",
		"url"  : "?model=rdproject_task_tkover&action=toAudit&taskId=",
		"choose" : "1"
	},{
		"name":"ǰ��������ϸ",
		"url" : "?model=rdproject_task_tkfront&action=toViewFrontDetail&jsUrl="+jsUrl+"&taskId=",
		"choose" : "2"
	},{
		"name":"ִ���ձ�",
		"url" : "?model=rdproject_worklog_rdworklog&action=worklogInTask&jsUrl="+jsUrl+"&id=",
		"choose" : "3"
	},{
		"name":"�����¼",
		"url" : "?model=log_change_change&action=page&tabId=4" + plus,
		"choose" : "4"
	},{
		"name":"������¼",
		"url" : "?model=log_operation_operation&action=page&tabId=5" + plus,
		"choose" : "5"
	},{
		"name":"����",
		"url" : "?model=file_uploadfile_management&action=rdpage&tabId=6"
					+ plus,
		"choose" : "6"
	}

];
var arrayTop = [{
		"name" : "所有日志",
		"url"  : "?model=rdproject_worklog_rdweeklog",
		"choose" : "basicMsg"
	},{
		"name":"重点关注",
		"url"  : "?model=rdproject_personfocus_personfocus",
		"choose" : "personfocus"
	},{
		"name" : "下属日志",
		"url"  : "?model=rdproject_worklog_rdweeklog&action=subordinateLog",
		"choose" : "subordinateLog"
	},{
		"name":"查询日志",
		"url"  : "?model=rdproject_worklog_rdweeklog&action=searchLog",
		"choose" : "searchLog"
	}
	,{
		"name":"查询工作进展",
		"url"  : "?model=rdproject_task_rdtask&action=workSchedule",
		"choose" : "workschedule"
	}
//	,{
//		"name":"日志报告生成",
//		"url"  : "?model=rdproject_worklog_rdweeklog&action=logReport",
//		"choose" : "logreport"
//	}
	,{
		"name":"关注人员列表",
		"url"  : "?model=rdproject_personfocus_personfocus&action=focusPage",
		"choose" : "focusPerson"
	}
];
var myWeeklog = [{
		"name" : "所有日志",
		"url"  : "?model=rdproject_worklog_rdweeklog&action=myWeeklog",
		"choose" : "myWeeklog"
	}
];
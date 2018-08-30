var plus = "&varName=arrayTop&jsUrl=view/template/rdproject/project/js/rdproject-manage-topTab.js&objTable=oa_rd_project&objId=";
var arrayTop = [{
			"name" : "项目仪表盘",
			"url" : "?model=rdproject_project_rdproject&action=rpOpenManage&pjId=",
			"choose" : "pjDashboard"
		}, {
			"name" : "基本信息",
			"url" : "?model=rdproject_project_rdproject&action=rpManageBasic&pjId=",
			"choose" : "basicMsg"
		}, {
			"name" : "里程碑信息",
			"url" : "?model=rdproject_milestone_rdmilestone&action=rmListManage&pjId=",
			"choose" : "lichengbei"
		},
		// {
		// "name":"虚拟办公室",
		// "url" :
		// "?model=rdproject_bbs_rdbbs&action=rbList&varName=arrayTop&jsUrl=view/template/rdproject/project/js/rdproject-manage-topTab.js&pjId=",
		// "choose" : "bbs"
		// },
		{
			"name" : "项目角色",
			"url" : "?model=rdproject_role_rdrole&action=rolePageInOption&{pjId}&pjId=",
			"choose" : "pjRole"
		}, {
			"name" : "项目成员",
			"url" : "?model=rdproject_team_rdmember&action=playerInOption&pjId=",
			"choose" : "pjTeam"
		}, {
			"name" : "项目任务",
			"url" : "?model=rdproject_task_rdtask&action=toProTkFrame&jsUrl=view/template/rdproject/project/js/rdproject-manage-topTab.js&pjId=",
			"choose" : "pjTask"
		}, {
			"name" : "项目日历",
			"url" : "?model=rdproject_task_rdtask&action=toProjectTaskCalendar&jsUrl=view/template/rdproject/project/js/rdproject-manage-topTab.js&pjId=",
			"choose" : "pjCanlendar"
		}, {
			"name" : "项目周报",
			"url" : "?model=rdproject_worklog_rdweeklog&action=listInProject&jsUrl=view/template/rdproject/project/js/rdproject-manage-topTab.js&pjId=",
			"choose" : "pjWeeklog"
		},
		// {
		// "name":"项目绩效",
		// "url" :
		// "?model=log_operation_operation&action=developing&tabId=subordinateLog2"+plus,
		// "choose" : "subordinateLog2"
		// },
		// {
		// "name":"项目成本",
		// "url" :
		// "?model=log_operation_operation&action=developing&tabId=subordinateLog3"+plus,
		// "choose" : "subordinateLog3"
		// },
		{
			"name" : "变更记录",
			"url" : "?model=log_change_change&action=page&tabId=biangeng"
					+ plus,
			"choose" : "biangeng"
		}, {
			"name" : "操作记录",
			"url" : "?model=log_operation_operation&action=page&tabId=caozuo"
					+ plus,
			"choose" : "caozuo"
		}
		// ,{
		// "name" : "权限",
		// "url" :
		// "?model=log_operation_operation&action=developing&tabId=subordinateLog"
		// + plus,
		// "choose" : "subordinateLog"
		// }
		, {
			"name" : "附件",
			"url" : "?model=file_uploadfile_management&action=rdPageManage&tabId=upfile"
					+ plus,
			"choose" : "upfile"
		}];
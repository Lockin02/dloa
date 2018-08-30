var plus = "&varName=arrayTop&jsUrl=view/template/rdproject/project/js/rdproject-update-topTab.js&objTable=oa_rd_project&objId=";
var arrayTop = [{
		"name" : "项目信息",
		"url"  : "?model=rdproject_project_rdproject&action=rpUpdateTo&pjId=",
		"choose" : "basicMsg"
	},{
		"name":"里程碑计划",
		"url"  : "?model=rdproject_milestone_rdmilestone&action=rmList&pjId=",
		"choose" : "milestonePlan"
	},{
		"name" : "项目角色",
		"url"  : "?model=rdproject_role_rdrole&action=toProjectRolePage&pjId=",
		"choose" : "pjRole"
	},{
		"name" : "项目成员",
		"url"  : "?model=rdproject_team_rdmember&action=player&pjId=",
		"choose" : "pjTeam"
	},{
//		"name" : "项目任务",
//		"url"  : "?model=rdproject_task_rdtask&action=toProTkFrame&jsUrl=view/template/rdproject/project/js/rdproject-update-topTab.js&readType=exam&pjId=",
//		"choose" : "pjTask"
//	},{
//		{
//		"name":"项目预算",
//		"url"  : "?model=center_projectmanagement&action=budget",
//		"choose" : "pjBudget"
//	}
//	,{
//		"name":"附件",
//		"url"  : "?model=center_projectmanagement&action=file",
//		"choose" : "annex"
//}
//		{
//		"name":"项目预算",
//		"url"  : "?model=log_operation_operation&action=developing&tabId=subordinateLog1" + plus,
//		"choose" : "subordinateLog1"
//	}
//	,
		"name":"附件",
		"url"  : "?model=file_uploadfile_management&action=rdPageManage&tabId=upfile" + plus,
		"choose" : "upfile"
}
];
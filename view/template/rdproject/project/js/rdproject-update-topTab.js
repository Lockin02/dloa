var plus = "&varName=arrayTop&jsUrl=view/template/rdproject/project/js/rdproject-update-topTab.js&objTable=oa_rd_project&objId=";
var arrayTop = [{
		"name" : "��Ŀ��Ϣ",
		"url"  : "?model=rdproject_project_rdproject&action=rpUpdateTo&pjId=",
		"choose" : "basicMsg"
	},{
		"name":"��̱��ƻ�",
		"url"  : "?model=rdproject_milestone_rdmilestone&action=rmList&pjId=",
		"choose" : "milestonePlan"
	},{
		"name" : "��Ŀ��ɫ",
		"url"  : "?model=rdproject_role_rdrole&action=toProjectRolePage&pjId=",
		"choose" : "pjRole"
	},{
		"name" : "��Ŀ��Ա",
		"url"  : "?model=rdproject_team_rdmember&action=player&pjId=",
		"choose" : "pjTeam"
	},{
//		"name" : "��Ŀ����",
//		"url"  : "?model=rdproject_task_rdtask&action=toProTkFrame&jsUrl=view/template/rdproject/project/js/rdproject-update-topTab.js&readType=exam&pjId=",
//		"choose" : "pjTask"
//	},{
//		{
//		"name":"��ĿԤ��",
//		"url"  : "?model=center_projectmanagement&action=budget",
//		"choose" : "pjBudget"
//	}
//	,{
//		"name":"����",
//		"url"  : "?model=center_projectmanagement&action=file",
//		"choose" : "annex"
//}
//		{
//		"name":"��ĿԤ��",
//		"url"  : "?model=log_operation_operation&action=developing&tabId=subordinateLog1" + plus,
//		"choose" : "subordinateLog1"
//	}
//	,
		"name":"����",
		"url"  : "?model=file_uploadfile_management&action=rdPageManage&tabId=upfile" + plus,
		"choose" : "upfile"
}
];
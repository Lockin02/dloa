var plus = "&varName=arrayTop&jsUrl=view/template/rdproject/project/js/rdproject-manage-topTab.js&objTable=oa_rd_project&objId=";
var arrayTop = [{
			"name" : "��Ŀ�Ǳ���",
			"url" : "?model=rdproject_project_rdproject&action=rpOpenManage&pjId=",
			"choose" : "pjDashboard"
		}, {
			"name" : "������Ϣ",
			"url" : "?model=rdproject_project_rdproject&action=rpManageBasic&pjId=",
			"choose" : "basicMsg"
		}, {
			"name" : "��̱���Ϣ",
			"url" : "?model=rdproject_milestone_rdmilestone&action=rmListManage&pjId=",
			"choose" : "lichengbei"
		},
		// {
		// "name":"����칫��",
		// "url" :
		// "?model=rdproject_bbs_rdbbs&action=rbList&varName=arrayTop&jsUrl=view/template/rdproject/project/js/rdproject-manage-topTab.js&pjId=",
		// "choose" : "bbs"
		// },
		{
			"name" : "��Ŀ��ɫ",
			"url" : "?model=rdproject_role_rdrole&action=rolePageInOption&{pjId}&pjId=",
			"choose" : "pjRole"
		}, {
			"name" : "��Ŀ��Ա",
			"url" : "?model=rdproject_team_rdmember&action=playerInOption&pjId=",
			"choose" : "pjTeam"
		}, {
			"name" : "��Ŀ����",
			"url" : "?model=rdproject_task_rdtask&action=toProTkFrame&jsUrl=view/template/rdproject/project/js/rdproject-manage-topTab.js&pjId=",
			"choose" : "pjTask"
		}, {
			"name" : "��Ŀ����",
			"url" : "?model=rdproject_task_rdtask&action=toProjectTaskCalendar&jsUrl=view/template/rdproject/project/js/rdproject-manage-topTab.js&pjId=",
			"choose" : "pjCanlendar"
		}, {
			"name" : "��Ŀ�ܱ�",
			"url" : "?model=rdproject_worklog_rdweeklog&action=listInProject&jsUrl=view/template/rdproject/project/js/rdproject-manage-topTab.js&pjId=",
			"choose" : "pjWeeklog"
		},
		// {
		// "name":"��Ŀ��Ч",
		// "url" :
		// "?model=log_operation_operation&action=developing&tabId=subordinateLog2"+plus,
		// "choose" : "subordinateLog2"
		// },
		// {
		// "name":"��Ŀ�ɱ�",
		// "url" :
		// "?model=log_operation_operation&action=developing&tabId=subordinateLog3"+plus,
		// "choose" : "subordinateLog3"
		// },
		{
			"name" : "�����¼",
			"url" : "?model=log_change_change&action=page&tabId=biangeng"
					+ plus,
			"choose" : "biangeng"
		}, {
			"name" : "������¼",
			"url" : "?model=log_operation_operation&action=page&tabId=caozuo"
					+ plus,
			"choose" : "caozuo"
		}
		// ,{
		// "name" : "Ȩ��",
		// "url" :
		// "?model=log_operation_operation&action=developing&tabId=subordinateLog"
		// + plus,
		// "choose" : "subordinateLog"
		// }
		, {
			"name" : "����",
			"url" : "?model=file_uploadfile_management&action=rdPageManage&tabId=upfile"
					+ plus,
			"choose" : "upfile"
		}];
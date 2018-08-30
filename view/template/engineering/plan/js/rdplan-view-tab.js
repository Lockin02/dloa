var plus = "&varName=arrayTop&jsUrl=view/template/engineering/plan/js/rdplan-view-tab.js&objTable=oa_rd_project_plan&objId=";
var arrayTop = [{
		"name" : "基本信息",
		"url"  : "?model=engineering_plan_rdplan&action=view&pnId=",
		"choose" : "basicMsg"
	}, {
		"name" : "操作记录",
		"url" : "?model=log_operation_operation&action=page&tabId=5" + plus,
		"choose" : "5"
	},{
		"name" : "权限",
		"url"  : "?model=engineering_plan_rdpurview&action=purviewPlan&pnId=",
		"choose" : "purviewPlan"
	},{
		"name":"附件",
		"url"  : "?model=file_uploadfile_management&action=rdpage&tabId=upfile" + plus,
		"choose" : "upfile"
	}
];
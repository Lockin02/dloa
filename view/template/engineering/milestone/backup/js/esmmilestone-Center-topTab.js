var plus = "&varName=arrayTop&jsUrl=view/template/engineering/milestone/js/rdmilestone-Center-topTab.js&objTable=oa_rd_project_plan&objId=";
var arrayTop = [
//	{
//		"name" : "属性",
//		"url"  : "?model=log_operation_operation&action=developing&tabId=subordinateLog"+plus,
//		"choose" : "subordinateLog"
//	},
		{
		"name":"里程碑",
		"url"  : "?model=engineering_milestone_rdmilestone&action=rmListCenter&pjId=",
		"choose" : "milestonePlan"
	},
		{
		"name" : "关联",
		"url"  : "?model=log_operation_operation&action=developing&tabId=subordinateLog1"+plus,
		"choose" : "subordinateLog1"
	},{
		"name":"版本",
		"url"  : "?model=log_operation_operation&action=developing&tabId=subordinateLog2"+plus,
		"choose" : "subordinateLog2"
	},{
		"name":"变更记录",
		"url"  : "?model=log_change_change&action=page&tabId=biangeng"+plus,
		"choose" : "biangeng"
	},{
		"name":"操作记录",
		"url"  : "?model=log_operation_operation&action=page&tabId=caozuo"+plus,
		"choose" : "caozuo"
	},{
		"name":"附件",
		"url"  : "?model=file_uploadfile_management&action=rdpage&tabId=upfile"+plus,
		"choose" : "upfile"
}];
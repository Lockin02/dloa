$(function() {
	if($("#exaResults").val() != ""){
		$(".auditInfo").show();
	}
	//提交审核人
	$("#assessmentName").yxselect_user({
		hiddenId : 'assessmentId',
		formCode : 'esmweeklogSubmit'
	});

	//表单验证
	validate({
		"assessmentName" : {
			required : true
		}
	});

	//工作日志
	$("#esmweeklogTable").yxeditgrid({
		// objName:'esmworklog[item]',
		url : '?model=engineering_worklog_esmworklog&action=listJson',
		type : 'view',
		title : '工作日志',
		param : {
			weekId : $("#weekId").val()
		},
		colModel : [ {
				display : '执行日期',
				name : 'executionDate',
				width : 80
			}, {
				display : '所在地',
				name : 'provinceCity',
				width : 80
			}, {
				display : '工作状态',
				name : 'workStatus',
				width : 70,
				datacode : 'GXRYZT'
			}, {
				display : '项目名称',
				name : 'projectName',
				width : 150
			},{
				display : '任务名称',
				name : 'activityName',
				width : 120
			},{
				display : '完成量',
				name : 'workloadDay',
				width : 70,
				process : function(v,row){
					return v + " " + row.workloadUnitName ;
				}
			}, {
				display : '费用',
				name : 'costMoney',
				width : 70,
				process : function(v,row){
					if(v * 1 == 0 || v == ''){
						return v;
					}else{
						return "<a href='javascript:void(0)' onclick='viewCost(\"" + row.id + "\",1)' title='点击查看费用'>" + moneyFormat2(v) + "</a>";
					}
				}
			}, {
				display : '工作描述',
				name : 'description'
			}
		]
	});
});

//进入查看费用页面
function viewCost(worklogId){
	var url = "?model=engineering_worklog_esmworklog&action=toView&id=" + worklogId;
	var height = 800;
	var width = 1150;
	window.open(url, "查看日志信息",
	'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width='
			+ width + ',height=' + height);
}
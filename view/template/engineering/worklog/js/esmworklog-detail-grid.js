$(function() {
	$("#esmworklogGrid").yxeditgrid("remove").yxeditgrid({
		url : '?model=engineering_worklog_esmworklog&action=listJson',
		type : 'view',
		param : {
			projectId : $("#projectId").val(),
			beginDateThan : $("#beginDate").val(),
			endDateThan : $("#endDate").val(),
			createId : $("#createId").val()
		},
		colModel : [{
				display : 'id',
				name : 'id',
				type : 'hidden'
			},{
				display : '执行日期',
				name : 'executionDate',
				width : 70
			}, {
				display : '填报人',
				name : 'createName',
				width : 80
			}, {
				display : '项目名称',
				name : 'projectName',
				width : 120,
				align : 'left'
			},{
				display : '任务名称',
				name : 'activityName',
				width : 120,
				align : 'left'
			},{
				display : '工作量',
				name : 'workloadDay',
				width : 60
			}, {
				display : '单位',
				name : 'workloadUnitName',
				width : 40
			}, {
				display : '任务进展',
				name : 'thisActivityProcess',
				width : 60,
				process : function(v){
					return v + " %";
				}
			}, {
				display : '项目进展',
				name : 'thisProjectProcess',
				width : 60,
				process : function(v){
					return v + " %";
				}
			}, {
				display : '人工投入占比',
				name : 'inWorkRate',
				width : 70,
				process : function(v){
					return v + " %";
				}
			}, {
				display : '费用',
				name : 'costMoney',
				width : 60,
				process : function(v,row){
					if(v * 1 == 0 || v == ''){
						return v;
					}else{
						return "<span class='blue'>" + moneyFormat2(v) + "</span>";
					}
				}
			}, {
				display : '工作描述',
				name : 'description',
				align : 'left'
			}, {
				display : '审核结果',
				name : 'assessResultName',
				width : 60
			}, {
				display : '审核建议',
				name : 'feedBack',
				align : 'left'
			}
		]
	});
});
$(function() {
	//默认显示日志视图
	initWorklog();
	$("#viewType").click(function(){
		if($(this).val() == "切换到周报视图"){//切换到周报视图
			$("#esmworklogGrid").yxeditgrid("remove");
			$(this).val("切换到日志视图");
			$("#title").text("周报统计");
			initWeeklog();
		}else{//切换到日志视图
			$("#esmweeklogGrid").yxeditgrid("remove");
			$(this).val("切换到周报视图");
			$("#title").text("日志统计");
			initWorklog();
		}
	})
});

//初始化待审核日志
function initWorklog(){
	var objGrid = $("#esmworklogGrid");
	//工作日志
	objGrid.yxeditgrid({
		url : '?model=engineering_worklog_esmworklog&action=searchDetailJson',
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
				display : '日期',
				name : 'executionDate',
				width : 120,
				process : function(v,row){
					if(row.id == "noId"){
						return "<span style='font-weight:bold;'>" + v + "</span>";
					}else{
						return v;
					}
				}
			}, {
				display : '工作状态',
				name : 'workStatus',
				datacode : 'GXRYZT',
				width : 80
			}, {
				display : '人工投入',
				name : 'inWorkRateOne',
				width : 100,
				process : function(v,row){
					if(row.id == "noId"){
						return "<span style='font-weight:bold;'>" + v + "</span>";
					}else{
						return v;
					}
				}
			}, {
				display : '工作系数',
				name : 'workCoefficient',
				width : 100,
				process : function(v,row){
					if(!v){
						return ;
					}
					if(row.id == "noId"){
						return "<span style='font-weight:bold;'>" + v + "</span>";
					}else{
						return v;
					}
				}
			}, {
				display : '项目完成量',
				name : 'thisProjectProcess',
				width : 100,
				process : function(v,row){
					if(!v){
						return ;
					}
					if(row.id == "noId"){
						return "<span style='font-weight:bold;'>" + v + " %</span>";
					}else{
						return v + " %";
					}
				}
			}, {
				display : '进展系数',
				name : 'processCoefficient',
				width : 100,
				process : function(v,row){
					if(!v){
						return ;
					}
					if(row.id == "noId"){
						return "<span style='font-weight:bold;'>" + v + "</span>";
					}else{
						return v;
					}
				}
			}, {
				display : '费用',
				name : 'costMoney',
				width : 100,
				process : function(v,row){
					if(!v){
						return ;
					}
					if(v * 1 == 0 || v == ''){
						return v;
					}else{
						if(row.id == "noId"){
							return "<span class='blue' style='font-weight:bold;'>" + moneyFormat2(v) + "</span>";
						}else{
							return "<span class='blue'>" + moneyFormat2(v) + "</span>";
						}
					}
				}
			}, {
				display : '考核结果',
				name : 'assessResultName',
				width : 100
			}, {
				display : '考核分数',
				name : 'assessScore',
				width : 100
			}, {
				display : '告警',
				name : 'warning',
				width : 100,
				process : function(v,row){
					if(!row.costMoney && row.id != 'noId'){
						return '<span class="red">未填写</span>';
					}
					if(row.confirmStatus == '0'){
						return '<span class="blue">未审核</span>';
					}
				}
			}, {
				display : '',
				name : ''
			}
		]
	});
}

//初始化待审核周报
function initWeeklog(){
	//参数判断
	var beginDateThan = $("#beginDate").val();
	var endDateThan = $("#endDate").val();
	var projectId = $("#projectId").val();

	var paramObj = {};
	if(beginDateThan)
		paramObj.beginDateThan=beginDateThan;
	if(endDateThan)
		paramObj.endDateThan=endDateThan;
	if(projectId)
		paramObj.projectId=projectId;

	var objGrid = $("#esmweeklogGrid");
	//项目周报
	objGrid.yxeditgrid({
		url : '?model=engineering_project_statusreport&action=warnView',
		type : 'view',
		param : paramObj,
		colModel : [{
				display : '周次',
				name : 'weekNo',
				align : 'center'
			}, {
				display : '告警',
				name : 'msg',
				process:function(v){
					return '<font color="red">'+v+'</font>';
				}
			}
		]
	});
}
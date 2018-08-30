$(document).ready(function() {
	/**
	 * 验证信息
	 */
	validate({
		"executionDate" : {
			required : true
		},
		"workStatus" : {
			required : true
		}
	});

	//实例化国家信息
	initCity();

	//费用类型
	if($("#invbody").html() != ""){
		showAndHide('feeImg','feeTbl');
	}

	//设置title
	initAmountTitle($("#feeRegular").val(),$("#feeSubsidy").val());

	//发票类型缓存
	billTypeArr = getBillType();
});

//调用任务信息
function initActivity(projectId){
	$("#activityName").yxcombogrid_esmactivity("remove");
	//如果项目id未定义，则获取页面中的项目id
	if(projectId == undefined){
		projectId = $("#projectId").val();
	}

	//工程项目渲染
	$("#activityName").yxcombogrid_esmactivity({
		hiddenId : 'activityId',
		nameCol : 'activityName',
		isShowButton : false,
		height : 250,
		gridOptions : {
			param : {"projectId":projectId , 'isLeaf' : 1},
			isTitle : true,
			showcheckbox : false
		}
	});
}

// 后台计算任务完成进度
function calTaskProcess(workload){
	if(workload == "" || workload *1 == 0){
		$("#workProcess").val('');
		return false;
	}
	if($("#id").length == 0){
		var worklogId = "";
	}else{
		var worklogId = $("#id").val();
	}
	var activityId = $("#activityId").val();
	$.ajax({
		type : "POST",
		url : "?model=engineering_activity_esmactivity&action=calTaskProcess",
		data : {
			"workload" : workload,
			"id" : activityId,
			"worklogId" : worklogId
		},
		success : function(msg) {
			if(msg != -1){
				$("#workProcess").val(msg);
			}else{
				alert('获取进度错误');
			}
		}
	});
}

//表单验证
function checkForm(){
	//任务判断 -- 如果任务当日已经填写过日志，不允许继续填写
	$.ajax({
		type : "POST",
		url : "?model=engineering_worklog_esmworklog&action=checkActivityLog",
		data : {
			"executionDate" : $("#executionDate").val(),
			"activityId" : $("#activityId").val()
		},
	    async: false,
		success : function(msg) {
			if(msg == "1"){
				if(confirm('当天日志已经填写，是否进入进行修改?')){
					location = "";
				}else{
					return false;
				}
			}
		}
	});
}
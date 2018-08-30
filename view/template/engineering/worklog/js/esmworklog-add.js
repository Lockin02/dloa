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

	//工程项目渲染
	$("#projectName").yxcombogrid_esmproject({
		hiddenId : 'projectId',
		nameCol : 'projectName',
		isShowButton : false,
		height : 250,
		isDown : true,
		gridOptions : {
			action : 'myProjectListPageJson',
			param : {'selectstatus' : 'GCXMZT02'},
			isTitle : true,
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#projectCode").val(data.projectCode);

				    $("#province").val(data.provinceId);//所属省份Id
				    $("#province").trigger("change");
					$("#city").val(data.cityId);//城市ID
					$("#city").trigger("change");
					$("#projectEndDate").val(data.planEndDate);//城市ID

					//清空任务相关信息
					clearActivity();

					//渲染任务
					initActivity(data.id);
				}
			},
			// 默认搜索字段名
			sortname : "c.updateTime",
			// 默认搜索顺序 降序DESC 升序ASC
			sortorder : "DESC"
		},
		event : {
			'clear' : function(){
				clearActivity();
			}
		}
	});

	//实例化国家信息
	initCity();

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
//			param : {"projectId":projectId , 'isLeaf' : 1 , 'memberIn' : $("#userId").val()},
			param : {"projectId":projectId , 'isLeaf' : 1 },
			isTitle : true,
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
					//先清空一些内容
					changeActivity();

					$("#workloadUnit").val(data.workloadUnit);
					$("#workloadUnitView").val(data.workloadUnit);
					$("#activityEndDate").val(data.planEndDate);//城市ID
				}
			}
		},
		event : {
			'clear' : function(){
				changeActivity();
			}
		}
	});
}

// 后台计算任务完成进度
function calTaskProcess(workload){
	if(workload == "" || workload *1 == 0){
		$("#workProcess").val('');
		return false;
	}
	var activityId = $("#activityId").val();
	if(activityId == "" || activityId*1 == 0){
		return false;
	}
	$.ajax({
		type : "POST",
		url : "?model=engineering_activity_esmactivity&action=calTaskProcess",
		data : {
			"workload" : workload,
			"id" : activityId
		},
		success : function(msg) {
			if(msg != -1){
				var processObj = eval("(" + msg + ")");
				$("#workProcess").val(processObj.process);
				$("#thisActivityProcess").val(processObj.thisActivityProcess);
				$("#thisProjectProcess").val(processObj.thisProjectProcess);
			}else{
				alert('获取进度错误');
			}
		}
	});
}

//表单验证
function checkForm(){
	var isTrue = true;
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
			if(msg != "0"){
				if(msg *1 == msg){
					if(confirm('当天日志已经填写，是否进入进行修改?')){
						isTrue = false;
						location = "?model=engineering_worklog_esmworklog&action=toEdit&id=" + msg;
					}else{
						isTrue = false;
					}
				}else{
					alert(msg);
					isTrue = false;
				}
			}
		}
	});
	return false;
//	return isTrue;
}
//工程项目查看页面
function showProject(projectId){
	var skey = "";
    $.ajax({
	    type: "POST",
	    url: "?model=engineering_project_esmproject&action=md5RowAjax",
	    data: { "id" : projectId },
	    async: false,
	    success: function(data){
	   	   skey = data;
		}
	});
	showModalWin("?model=engineering_project_esmproject&action=viewTab&id=" + projectId +"&skey=" + skey ,1);
}

//查看任务信息
function showActivity(activityId){
	var skey = "";
    $.ajax({
	    type: "POST",
	    url: "?model=engineering_activity_esmactivity&action=md5RowAjax",
	    data: { "id" : activityId },
	    async: false,
	    success: function(data){
	   	   skey = data;
		}
	});
	showOpenWin("?model=engineering_activity_esmactivity&action=toView&id=" + activityId +"&skey=" + skey ,'项目任务');
}

//选择工作量单位时验证
function changeWorkloadUnit(){
	//如果存在任务，则不允许修改工作量单位
	var activityId = $("#activityId").val();
	if(activityId != "" && activityId != 0){
		alert('涉及到任务的日志不允许调整工作量单位');
		$("#workloadUnitView").val($("#workloadUnit").val());
		return false;
	}else{
		$("#workloadUnit").val($("#workloadUnitView").val());
	}
}

//清空任务信息 - 单个日志填写用
function clearActivity(){
	//清空任务数据
	$("#actitityId").val("");
	$("#activityName").val("");
	$("#workProcess").val("");
	$("#workloadDay").val("");
	$("#thisActivityProcess").val("");
	$("#thisProjectProcess").val("");
}

//清空任务信息 - 单个日志填写用
function changeActivity(){
	//清空任务数据
	$("#workProcess").val("");
	$("#workloadDay").val("");
	$("#thisActivityProcess").val("");
	$("#thisProjectProcess").val("");
	$("#activityEndDate").val("");
}

//清空任务信息 - 多个日志填写用
function clearActivityBatch(rowNum){
	var beforeStr = "importTable_cmp_";
	//清空任务数据
	$("#"+ beforeStr + "actitityId" + rowNum).val("");
	$("#"+ beforeStr + "activityName" + rowNum).val("");
	$("#"+ beforeStr + "workProcess" + rowNum).val("");
	$("#"+ beforeStr + "workloadDay" + rowNum).val("");
	$("#"+ beforeStr + "thisActivityProcess" + rowNum).val("");
	$("#"+ beforeStr + "thisProjectProcess" + rowNum).val("");
	$("#"+ beforeStr + "activityEndDate" + rowNum).val("");
}

//清空 - 选择任务时
function changeActivityBatch(rowNum){
	var beforeStr = "importTable_cmp_";
	//清空任务数据
	$("#"+ beforeStr + "workProcess" + rowNum).val("");
	$("#"+ beforeStr + "workloadDay" + rowNum).val("");
	$("#"+ beforeStr + "thisActivityProcess" + rowNum).val("");
	$("#"+ beforeStr + "thisProjectProcess" + rowNum).val("");
	$("#" + beforeStr + "activityEndDate" + rowNum ).val("");
}

//获取省份信息
function getProvince(){
	//缓存省份信息
    var provinceArr;
	$.ajax({
		type : 'POST',
		url : "?model=system_procity_province&action=getProvinceForEditGrid",
		data : {
			"countryId" : '1'
		},
	    async: false,
		success : function(data) {
            provinceArr = eval("(" + data + ")");
		}
	});
    return provinceArr;
}

//城市数组获取
function getCity(provinceId){
	var cityArr;
	//如果存在缓存
	if(cityCache != undefined && cityCache[provinceId]){
		cityArr = cityCache[provinceId];
	}else{
		//缓存省份信息
		$.ajax({
			type : 'POST',
			url : "?model=system_procity_city&action=getCityForEditGrid",
			data : {
				"provinceId" : provinceId
			},
		    async: false,
			success : function(data) {
				cityArr = eval("(" + data + ")");

				//缓存城市信息
				cityCache[provinceId] = cityArr;
			}
		});
	}
	return cityArr;
}

//变更日志的城市信息
function changeCity(rowNum,provinceId,cityId){
	//重置城市内容
	var cityArr = getCity(provinceId);
	var beforeStr = "importTable_cmp_";

	//重置选择数组
	var cityObj = $("#" + beforeStr + "cityId" + rowNum );
	var cityStr;
	//清空选项
	cityObj.empty();
	var city;//默认城市
	var selected = '';
	for (var i = 0, l = cityArr.length; i < l; i++) {
		selected = '';
		//设置默认城市
		if(i == 0){
			city = cityArr[i].name;
		}
		//默认选中
		if(cityId == cityArr[i].value){
			selected = "selected='selected'";
			city = cityArr[i].name;
		}
		cityStr += "<option "+ selected + " value='" + cityArr[i].value + "' title='" + cityArr[i].name + "'>" + cityArr[i].name
			+ "</option>";
	}
	cityObj.append(cityStr);
	$("#" + beforeStr + "city" + rowNum ).val(city);
}

//调用任务信息
function initActivityBatch(projectId,rowNum){
	var beforeStr = "importTable_cmp_";
	var activityObj = $("#" + beforeStr + "activityName" + rowNum );

	//工程项目渲染
	activityObj.yxcombogrid_esmactivity("remove").yxcombogrid_esmactivity({
		hiddenId : beforeStr + 'activityId' + rowNum,
		nameCol : 'activityName',
		isShowButton : false,
		height : 250,
		width : 500,
		gridOptions : {
			param : {"projectId":projectId , 'isLeaf' : 1 , 'isTrial' : 0 },
			action : 'selectActForLog',
			isTitle : true,
			showcheckbox : false,
			// 表单
			colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '任务名称',
					name : 'activityNameView',
                    sortable : false
				}, {
					display : '任务名称',
					name : 'activityName',
					sortable : true,
					hide : true
				}, {
					display : '预计开始',
					name : 'planBeginDate',
					sortable : true,
					width : 80
				}, {
					display : '预计结束',
					name : 'planEndDate',
					sortable : true,
					width : 80
				}, {
					display : '预计工期',
					name : 'days',
					sortable : true,
					width : 70,
                    process : function(v,row){
                        return v;
                    }
				}, {
					display : '工作量',
					name : 'workload',
					sortable : true,
					process : function(v,row){
						return v + " " + row.workloadUnitName;
					},
					width : 80
				}, {
					display : '任务进度',
					name : 'process',
					sortable : true,
					process : function(v,row){
						return v + " %";
					},
					width : 80
				}, {
					display : '实际开始',
					name : 'actBeginDate',
					sortable : true,
					width : 80,
					hide : true
				}, {
					display : '实际结束',
					name : 'actEndDate',
					sortable : true,
					width : 80,
					hide : true
				}, {
		            name : 'remark',
		            display : '备注',
		            sortable : true,
					width : 150,
					hide : true
		        }],
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#" + beforeStr + "workloadUnit" + rowNum ).val(data.workloadUnit);
					$("#" + beforeStr + "workloadUnitView" + rowNum ).val(data.workloadUnitName);

					//清空
					changeActivityBatch(rowNum);

					$("#" + beforeStr + "activityEndDate" + rowNum ).val(data.planEndDate);
				}
			}
		},
		event : {
			'clear' : function(){
				changeActivityBatch(rowNum);
			}
		}
	});
}

// 后台计算任务完成进度
function calTaskProcessBatch(rowNum,workload){
	var beforeStr = "importTable_cmp_";
	if(workload == ""){
		$("#" + beforeStr + "workProcess" + rowNum).val('');
		return false;
	}
	var activityId = $("#" + beforeStr + "activityId" + rowNum).val();
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
				$("#" + beforeStr + "workProcess" + rowNum).val(processObj.process);
				$("#" + beforeStr + "thisActivityProcess" + rowNum).val(processObj.thisActivityProcess);
				$("#" + beforeStr + "thisProjectProcess" + rowNum).val(processObj.thisProjectProcess);
			}else{
				alert('获取进度错误');
			}
		}
	});
}

//获取对应日期最大工作量比例
function getMaxWorkRate(){
	//获取最大可天投入工作比例
	var executionDate = $("#executionDate").val();
	$.ajax({
		type : "POST",
		url : "?model=engineering_worklog_esmworklog&action=getMaxInWorkRate",
		data : {
			"executionDate" : executionDate
		},
		success : function(msg) {
			$("#maxInWorkRateShow").html(msg);

			//最大值设置
			var maxInWorkRate = $("#maxInWorkRate");
			if(maxInWorkRate.length != 0){
				maxInWorkRate.val(msg);
			}
		}
	});
	$("#executionDateShow").html(executionDate);
}

//批量新增日志部分 表单验证
function checkBatchAdd(){
	var executionDate = $("#executionDate").val();
	
	//判断日志日期是否存在请休假记录
    var workStatus = $('#workStatus').val();
	if(workStatus == 'GXRYZT-01' || workStatus == 'GXRYZT-02'){
		var isInHols = false;
		$.ajax({
			type : "POST",
			url : "?model=engineering_worklog_esmworklog&action=isInHols",
			data : {
				"executionDate" : executionDate
			},
			async: false,
			success : function(msg) {
				if(msg == '1'){
					alert("【" + executionDate + "】存在请休假记录，工作状态不能为工作或待命");
					isInHols = true;
				}
			}
		});
		if(isInHols){
			return false;
		}
	}
	
	//缓存内容表
	var importTableObj = $("#importTable");
	var beforeStr = "importTable_cmp_";

	//首先计算日期差
	var thisDate = $("#thisDate").val();
	var dayDiff = DateDiff(executionDate,thisDate);

	//首先检验任务是否重复填写日志
	var cmps = importTableObj.yxeditgrid("getCmpByCol", "inWorkRate");
	var activityCache = [];
    var projectCache = [];
	var hasSame = false; //存在相同任务
	var noActivity = false; //没有任务
	var noProject = false; //没有任务
	var activityId;
	var projectId;
	var inWorkRate = 0;
	var hasWorkloadDay = true; //存在完成量未填
	var maxLogDay;//最大填报日期
	var hasBiggerDate = false;
	cmps.each(function(i, n){
		if($("#esmworklog[detail]_" + i +"_isDelTag").length == 0){
			//行数获取
			var rowNum = $(this).data("rowNum");

			//日志部分验证
			activityId = $("#" + beforeStr + "activityId" + rowNum).val();
			projectId = $("#" + beforeStr + "projectId" + rowNum).val();
			if(projectId != "" && projectId*1 != 0){
                // 缓存项目ID
                if($.inArray(projectId, projectCache) == -1){
                    projectCache.push(projectId);
                }

				if($.inArray(activityId,activityCache) != -1){
					hasSame = true;
					return false;
				}else{
					activityCache.push(activityId);
				}

				//没有任务
				if(activityId == "" || activityId * 1 == 0){
					noActivity = true;
					return false;
				}

				//没有填工作量
				var workloadDay = $("#" + beforeStr + "workloadDay" + rowNum).val();
				if(workloadDay == ""){
					hasWorkloadDay = false;
					return false;
				}

				//最大填报日期
				maxLogDay = $("#" + beforeStr + "maxLogDay" + rowNum).val();
				if(maxLogDay*1 != 0 && maxLogDay < dayDiff){
					var activityName = $("#" + beforeStr + "activityName" + rowNum).val();
					alert('任务【'+ activityName +'】的日志超出项目最大填报期限');
					hasBiggerDate = true;
					return false;
				}
			}else if(activityId != "" && activityId * 1 != 0){
				//没有任务
				if(projectId == "" || projectId * 1 == 0){
					noProject = true;
					return false;
				}
			}

			//投入工作比例
			inWorkRate = accAdd(inWorkRate,this.value,2);
		}
        return true;
	});

	//存在重复
	if(hasSame == true){
		alert('同一个任务单天不能填写两份或两份以上日志');
		return false;
	}

	//没有任务
	if(noActivity == true){
		alert('存在有项目但是没有任务的行！');
		return false;
	}

	//没有项目
	if(noProject == true){
		alert('存在有任务但是没有项目的行！');
		return false;
	}

	//没有填工作量
	if(hasWorkloadDay == false){
		alert('已选择任务的日志，请填入完成量');
		return false;
	}

	//可投入工作比例
	var maxInWorkRate = $("#maxInWorkRateShow").html();
	if(maxInWorkRate*1 < inWorkRate*1){
		alert('填报投入工作比例【' + inWorkRate + "】已大于当日最大可投入工作比例【" +maxInWorkRate + "】！" );
		return false;
	}

	//超出最大填报日期
	if(hasBiggerDate == true){
		return false;
	}

    // 存在项目，则验证是否还可甜
    if (projectCache.length > 0) {
        //验证任务在对应日志内是否已填
        var projectWithoutDeadline = true;
        $.ajax({
            type : "POST",
            url : "?model=engineering_worklog_esmworklog&action=checkProjectWithoutDeadline",
            data : {
                "projectIds" : projectCache.toString(),
                "executionDate" : executionDate
            },
            async: false,
            success : function(msg) {
                if(msg != "1"){
                    projectWithoutDeadline = false;
                }
            }
        });

        //没有填工作量
        if(!projectWithoutDeadline){
            alert('存在项目超过截止填报日期');
            return false;
        }
    }

	//如果存在任务
	if(activityCache.toString() != ""){
		//验证任务在对应日志内是否已填
		var logIsExist = false;
		$.ajax({
			type : "POST",
			url : "?model=engineering_worklog_esmworklog&action=checkActivityLog",
			data : {
				"activityId" : activityCache.toString(),
				"executionDate" : executionDate,
				"searchType" : "mul"
			},
		    async: false,
			success : function(msg) {
				if(msg != "0" && msg != ""){
					alert(msg);
					logIsExist = true;
				}
			}
		});

        //没有填工作量
        if(logIsExist == true){
            return false;
        }
	}

	return true;
}

//审核结果翻译
function auditResult(assessResult){
	assessResult = assessResult + "";
	switch(assessResult){
		case '0' : return '';break;
		case '1' : return '优';break;
		case '2' : return '良';break;
		case '3' : return '中';break;
		case '4' : return '差';break;
		case '5' : return '打回';break;
		default : return '';
	}
}

//审核结果翻译
function keyValue(assessResult){
	assessResult = assessResult + "";
	switch(assessResult){
		case '1' : return 'excellent';break;
		case '2' : return 'good';break;
		case '3' : return 'medium';break;
		case '4' : return 'poor';break;
		case '5' : return 'back';break;
        default : return assessResult;
	}
}

//验证投入工作比例
function checkWorkRate(){
    var inWorkRateObj = $("#inWorkRate");
	var inWorkRateVal = inWorkRateObj.val();
	if(isNaN(inWorkRateVal) || (parseInt(inWorkRateVal) > 100 || parseInt(inWorkRateVal) < 0)){
		alert('请输入 0 到 100 以内的数字');
		inWorkRateObj.val('');
	}
}

//显示loading
function showLoading(){
	$("#loading").show();
}

//隐藏
function hideLoading(){
	$("#loading").hide();
}
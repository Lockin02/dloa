//新增审批调用页面
function audit(){
	document.getElementById('form1').action="?model=engineering_project_statusreport&action=add&act=audit";
}

//编辑审批调用页面
function auditEdit(){
	document.getElementById('form1').action="?model=engineering_project_statusreport&action=edit&act=audit";
}
/****************** 新周报处理 *********************/
//项目预警
function initWeekWarning(){
	$.ajax({
	    type: "POST",
	    url: "?model=engineering_weekreport_weekwarning&action=getWeek",
	    data: {
			"projectId" : $("#projectId").val(),
			'projectCode':$("#projectCode").val(),
			'weekNo' : $("#weekNo").val() ,
			'mainId' : $("#id").val()
		},
        async: false,
	    success: function(data){
			$("#weekWarning").html(data);

            // 获取需要做验证的内容
            if (validateArr) {
                $("input[id^='needFeedback']").each(function() {
                    var str = "feedback" + $(this).val();

                    // 验证赋值
                    validateArr[str] = {
                        required : true
                    };
                });
            }
		}
	});
}

//项目预警
function viewWeekWarning(){
	$.ajax({
	    type: "POST",
	    url: "?model=engineering_weekreport_weekwarning&action=viewWeek",
	    data: {
	    	"projectId" : $("#projectId").val(),
	    	'projectCode':$("#projectCode").val(),
	    	'weekNo' : $("#weekNo").val(),
	    	'mainId' : $("#id").val()
	    },
	    success: function(data){
			$("#weekWarning").html(data);
		}
	});
}

//周状态处理
function initWeekStatus(){
	$.ajax({
	    type: "POST",
	    url: "?model=engineering_weekreport_weekstatus&action=getWeekStatus",
	    data: {"projectId" : $("#projectId").val(),'weekNo' : $("#weekNo").val() ,'mainId' : $("#id").val()},
	    success: function(data){
			$("#weekStatus").html(data);
		}
	});
}

//周状态查看
function viewWeekStatus(){
	$.ajax({
	    type: "POST",
	    url: "?model=engineering_weekreport_weekstatus&action=viewWeekStatus",
	    data: {"projectId" : $("#projectId").val(),'weekNo' : $("#weekNo").val(),'mainId' : $("#id").val()},
	    success: function(data){
			$("#weekStatus").html(data);
		}
	});
}

//任务进度
function initWeekTask(){
	$.ajax({
	    type: "POST",
	    url: "?model=engineering_weekreport_weektask&action=getWeekTask",
	    data: {"projectId" : $("#projectId").val(),'weekNo' : $("#weekNo").val() ,'mainId' : $("#id").val()},
	    success: function(data){
			$("#weektask").html(data);
		}
	});
}

//任务进度
function viewWeekTask(){
	$.ajax({
	    type: "POST",
	    url: "?model=engineering_weekreport_weektask&action=viewWeekTask",
	    data: {"projectId" : $("#projectId").val(),'mainId' : $("#id").val()},
	    success: function(data){
			$("#weektask").html(data);
		}
	});
}

//点击弹出项目查看界面
function viewProject(){
	var projectId = $("#projectId").val();
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
	showModalWin("?model=engineering_project_esmproject&action=viewTab&&id=" + projectId +"&skey=" + skey ,1,projectId);
}

//显示loading
function showLoading(){
	$("#loading").show();
}

//隐藏
function hideLoading(){
	$("#loading").hide();
}
//������������ҳ��
function audit(){
	document.getElementById('form1').action="?model=engineering_project_statusreport&action=add&act=audit";
}

//�༭��������ҳ��
function auditEdit(){
	document.getElementById('form1').action="?model=engineering_project_statusreport&action=edit&act=audit";
}
/****************** ���ܱ����� *********************/
//��ĿԤ��
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

            // ��ȡ��Ҫ����֤������
            if (validateArr) {
                $("input[id^='needFeedback']").each(function() {
                    var str = "feedback" + $(this).val();

                    // ��֤��ֵ
                    validateArr[str] = {
                        required : true
                    };
                });
            }
		}
	});
}

//��ĿԤ��
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

//��״̬����
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

//��״̬�鿴
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

//�������
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

//�������
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

//���������Ŀ�鿴����
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

//��ʾloading
function showLoading(){
	$("#loading").show();
}

//����
function hideLoading(){
	$("#loading").hide();
}